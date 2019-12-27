<?php
/**
 * Created by wangjie
 * Author: wangjie
 * Date: 2019/11/7
 * Time: 14:02
 */
session_start();

//引入数据库
require './common/init.php';
//引入公共函数
require  './common/function.php';

$username = input('post','username','s');;
$password = input('post','password','s');

//接收$_POST['Info_title']并指定类型为字符串
$title = input('post','Info_title','s');
//接收$_GET['id']并指定类型为整型
$id = input('get','id','d');
//接收$_GET['page']并指定类型为整型,默认值为1
//$page = input('get','page','d','1');

//实现分页查询
//获取当前页码，限制最小值为1
$page = max(input('get','page','d'),1);
//每页显示三条
$size = 3;
//留言分页查询
/**
 * 如果您使用 ORDER BY 关键词，记录集的排序顺序默认是升序（1 在 9 之前，"a" 在 "p" 之前）。
 * 使用 DESC 关键词来设定降序排序（9 在 1 之前，"p" 在 "a" 之前）
 *
 * 可以根据多个列进行排序。当按照多个列进行排序时，只有第一列相同时才使用第二列：
 * SELECT column_name(s) FROM table_name ORDER BY column_name1, column_name2
**/
 $sql = 'SELECT `id`,`Info_title`,`content`,`create_time`,`user_id` FROM `text` ORDER BY `id` DESC LIMIT '.page_sql($page,$size);
//使用init.php中的$link
if(!$res = mysqli_query($link,$sql)){
    exit("SQL[$sql]执行失败：".mysqli_error($link));
}
//获取留言总数
$sql2 = 'SELECT count(*) FROM `text`';
if(!$res2 = mysqli_query($link,$sql2)){
    exit("SQL[$sql2]执行失败：".mysqli_error($link));
}
$total = (int)mysqli_fetch_row($res2)[0];
//获取回复内容
$data = mysqli_fetch_all($res,MYSQLI_ASSOC);
//print_r($data);
//查询结果为空时，自动返回第一页
if(empty($data)&& $page>1){
    header('Location:index.php?page=1');
    exit;
}
mysqli_free_result($res);

//遍历$data（为主题内容）
foreach ($data as $key => $row){
//    var_dump($row);
//   根据$data数组中的id 查回复留言内容
    $replay_sql = "SELECT * FROM replay_table WHERE text_id = {$row['id']}";
    if(!$res3 = mysqli_query($link,$replay_sql)){
        exit("SQL[$replay_sql]执行失败：".mysqli_error($link));
    }
    /**
    mysqli_fetch_all(mysqli_result $result[,int $resulttype])
        功能：从结果集中获取数据。
        参数：
        result mysqli_query执行有返回结果集sql语句的返回。
        resulttype 获取数组的格式
        MYSQLI_BOTH 关联和索引数组
        MYSQLI_ASSOC 关联数组
        MYSQLI_NUM 索引数组
        返回：一次取回所有查询的数据。取回数据的格式参考参数resulttype。
     * */
    $get_result = mysqli_fetch_all($res3,MYSQLI_ASSOC);
    //    print_r($get_result);

    //    根据回复中的re_userId来添加相应的用户
    foreach ($get_result as $k => $v){
        //根据在$get_result中的re_userId查找用户
        $user_sql  = "SELECT * FROM user_table WHERE user_id ={$v['re_userId']}";
        if(!$res4  = mysqli_query($link,$user_sql)){
            exit("SQL[$user_sql]执行失败：".mysqli_error($link));
        }
        /**mysqli_fetch_assoc 从结果集中获取数据，格式是关联数组 $arr1 = array('userId'=>23,'username'=>'md5',
                    'password'=>'aa2313sd2323441222')
        访问：$arr1['userId']=23;$arr1['username']='md5';...
        mysqli_fetch_row 从结果集中获取数据，格式是索引数组  $arr2 = array('userId','username','password');
         访问“$arr2[0]='userId',$arr2[1]='username';...
        */
         $get_user = mysqli_fetch_row($res4);
        //   print_r($get_result);
        //   $k为数组$get_result的索引,通过这个索引把取出来的用户信息组装到回复的数组中
        $get_result[$k]['re_userId'] = $get_user;
    }
    //    print_r($get_result);
    //    查询到的数据存到$data数组中
    //    $data[$key]["replay"] = $get_result;
    //    把回复的数据组装到主题里面
    $data[$key]["replay"] = $get_result;
    $createUser = "SELECT * FROM user_table WHERE user_id = {$row['user_id']}";
    if(!$res5  = mysqli_query($link,$createUser)){
        exit("SQL[$createUser]执行失败：".mysqli_error($link));
    }
    $getCreateUser = mysqli_fetch_row($res5);
    $data[$key]["user_id"] = $getCreateUser;
//    var_dump($data);
//    print_r($data);
}

//获取待编辑的留言id和页面的名字，根据页面的名字跳转到相应的页
$id = input('get','id','d','0');
$re_id = input('get','re_id','d','0');
$action = input('get','action','s');
if($id || $re_id){
    //检查session中有无user_id
    if(!$_SESSION['user_id']){
//        如果没有session跳转到登录页面
        echo "<script>alert('您还尚未登录！点击确定跳转到登录页面~~');</script>";
        echo "<a href='index.php'><p style='font-size: 16px;text-align: center'>如果跳转失败请点击这里~~</p></a>";
        //跳转到login页面
        header("Refresh:1;url=./view/html/login.html");
    }elseif($action=='edit'){
        $edit_sql = 'SELECT `Info_title`,`content` FROM  `text` WHERE `id`=' . $id;
        if (!$res = mysqli_query($link, $edit_sql)) {
            exit("SQL[$edit_sql]执行失败：" . mysqli_error($link) . $edit_sql);
        }
        //$edit为根据id取到留言所有主题和内容的数组，并在一下页面中根据页面展示语句来显示
        if (!$edit = mysqli_fetch_assoc($res)) {
            require './view/html/edit.html';
        } else {
            require './view/html/edit.html';
        }
        mysqli_free_result($res);
    }elseif($action=='replay') {
            //根据id取到回复的内容
            $_SESSION['text_id'] = $id;
            $rep_sql ='SELECT `replay`,`replay_time` FROM  `replay_table` WHERE `text_id`=' . $id;
            if(!$res = mysqli_query($link,$rep_sql)){
                exit("SQL[$rep_sql]执行失败：".mysqli_error($link).$rep_sql);
            }
            //查询到回复的内容，并在html页面中显示
            if (!$replay = mysqli_fetch_assoc($res)){
                require './view/html/replay.html';
            }else{
                require './view/html/replay.html';
            }
            // 开启外键约束检查，以保持表结构完整性
            $sql_reset = 'SET foreign_key_checks = 1;';
            if(!mysqli_query($link,$sql_reset)){
                exit('SQL执行失败：' . mysqli_error($link));
            }
            mysqli_free_result($res);
    }elseif ($action=='delete'){
            // 先设置外键约束检查关闭
            $sql_set = 'SET foreign_key_checks = 0;';
            if(!mysqli_query($link,$sql_set)){
                exit('SQL执行失败：' . mysqli_error($link));
            }
            $sql = 'DELETE FROM `text` WHERE `id`=' . $id;
            if(!mysqli_query($link,$sql)){
                exit('SQL执行失败：' . mysqli_error($link));
            }
            // 开启外键约束检查，以保持表结构完整性
            $sql_reset = 'SET foreign_key_checks = 1;';
            if(!mysqli_query($link,$sql_reset)){
                exit('SQL执行失败：' . mysqli_error($link));
            }
        echo "<script>alert('删除内容成功！正在刷新页面~~');</script>";
        echo "<a href='index.php'><p style='font-size: 16px;text-align: center'>如果跳转失败请点击这里~~</p></a>";
        //跳转到login页面
        header("Refresh:1;url=./index.php");
    }elseif ($action=='delReplay'){
        // 先设置外键约束检查关闭
        $sql_set = 'SET foreign_key_checks = 0;';
        if(!mysqli_query($link,$sql_set)){
            exit('SQL执行失败：' . mysqli_error($link));
        }
        $sql = 'DELETE FROM `replay_table` WHERE `re_id`=' . $re_id;
        if(!mysqli_query($link,$sql)){
            exit('SQL执行失败：' . mysqli_error($link));
        }
        // 开启外键约束检查，以保持表结构完整性
        $sql_reset = 'SET foreign_key_checks = 1;';
        if(!mysqli_query($link,$sql_reset)){
            exit('SQL执行失败：' . mysqli_error($link));
        }
        echo "<script>alert('删除回复成功！正在刷新页面~~');</script>";
        echo "<a href='index.php'><p style='font-size: 16px;text-align: center'>如果跳转失败请点击这里~~</p></a>";
        //跳转到login页面
        header("Refresh:1;url=./index.php");
    }

}
require './view/index.html';
mysqli_close($link);
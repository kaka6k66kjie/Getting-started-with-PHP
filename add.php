<?php
/**
 * Created by viruser
 * Author: viruser
 * Date: 2019/12/2
 * Time: 13:24
 **/

session_start();
@$username = $_POST['username'];
@$password = $_POST['password'];

require './common/init.php';
require './common/function.php';

//接收index.html添加留言表单提交过来的数据，接收变量
$Info_title = trim(input('post','Info_title','s'));
$content = trim(input('post','Info_content','s'));
//当名称为空时，使用'木有标题'作为默认值
$Info_title = $Info_title ?:'木有标题';
if($content==null){
    return '/PHPProjects/view/';
}
//保存用户发留言布时间
$create_time = time();
$user_id = $_SESSION['user_id'];
//检查session，如果session中没有存数据，返回到登录页面
if(! $_SESSION['user_id']){
    echo "<script>alert('您还尚未登录！点击确定跳转到登录页面~~');</script>";
    echo "<a href='index.php'><p style='font-size: 16px;text-align: center'>如果跳转失败请点击这里~~</p></a>";
    //跳转到login页面
    header("Refresh:1;url=./view/html/login.html");
}else{
    $sql = 'INSERT INTO `text` (`Info_title`,`content`,`create_time` ,`user_id`) VALUES  (?,?,?,?)';
    if(!$stmt = mysqli_prepare($link,$sql)){
        exit("SQL[$sql]预处理失败：".mysqli_error($link));
    }
    mysqli_stmt_bind_param($stmt,'sssd',$Info_title,$content,$create_time,$user_id);
    if(!mysqli_stmt_execute($stmt)){
        exit('数据库操作失败：'.mysqli_stmt_error($stmt));
    }
    echo "<script>alert('添加成功！正在刷新页面~~');</script>";
    echo "<a href='index.php'><p style='font-size: 16px;text-align: center'>如果跳转失败请点击这里~~</p></a>";
    //跳转到login页面
    header("Refresh:1;url=./index.php");
}


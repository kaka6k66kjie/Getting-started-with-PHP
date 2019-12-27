<?php
/**
 * Created by viruser
 * Author: viruser
 * Date: 2019/11/25
 * Time: 19:32
 **/

session_start();
//引入数据库文件
require './common/init.php';
require './common/function.php';
/*
    mysqli_stmt_get_result() - 从准备好的语句获取结果集
    mysqli_stmt_bind_param() - 将变量绑定到准备好的语句作为参数
    mysqli_stmt_execute() - 执行准备好的查询
    mysqli_stmt_fetch() - 从准备好的语句中获取结果到绑定变量中
    mysqli_prepare() - 准备执行一个SQL语句
    mysqli_stmt_prepare() - 准备要执行的SQL语句
 */
//接收replay.html提交过来的数据，接收变量
//trim()去除字符串开始和结束位置多余的空格
$replay = trim(input('post','Replay_content','s'));
//保存用户回复的时间
$relay_time = time();

if($_SESSION['text_id']){
        //更新数据库
        //根据获取到的text_id添加回复的内容以及时间
        $text_id = $_SESSION['text_id'];
        $re_userId = $_SESSION['user_id'];
        // 开启外键约束检查，以保持表结构完整性
        $sql_reset = 'SET foreign_key_checks = 0;';
        if(!mysqli_query($link,$sql_reset)){
            exit('SQL执行失败：' . mysqli_error($link));
        }
        $sql = 'INSERT INTO `replay_table` (`replay`,`replay_time`,`text_id`,`re_userId`) VALUES  (?,?,?,?)';
        //  mysqli_prepare 准备执行一个SQL语句
        if(!$stmt=mysqli_prepare($link,$sql)){
            exit("SQL[$sql]预处理失败：".mysqli_error($link));
        }
        //  将变量绑定到准备好的语句以存储结果
        mysqli_stmt_bind_param($stmt,'ssdd',$replay,$relay_time,$text_id,$re_userId);
        //  执行编译好的SQL语句
        if (!mysqli_stmt_execute($stmt)){
            exit('数据库操作失败！:'.mysqli_stmt_error($stmt));
        }
        // 开启外键约束检查，以保持表结构完整性
        $sql_reset = 'SET foreign_key_checks = 1;';
        if(!mysqli_query($link,$sql_reset)){
            exit('SQL执行失败：' . mysqli_error($link));
        }
        header("Location:index.php?page=$page");
        exit();
}



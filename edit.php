<?php
/**
 * Created by viruser
 * Author: viruser
 * Date: 2019/11/25
 * Time: 19:07
 **/
require './common/init.php';
require './common/function.php';

//接收edit.html提交过来的数据，接收变量
$Info_title = trim(input('post','Edit_title','s'));
$content = trim(input('post','Edit_content','s'));
//$username = trim(input('post','username','s'));
//$password = trim(input('post','password','d'));
//获取待修改留言的id
$id = max(input('get','id','d'),0);
if($id){
    $sql = "UPDATE `text` SET `Info_title`=?,`content`=? WHERE `id`=?;";
    if(!$stmt=mysqli_prepare($link,$sql)){
        exit("SQL[$sql]预处理失败：".mysqli_error($link));
    }
    mysqli_stmt_bind_param($stmt,'ssd',$Info_title,$content,$id);
    if (!mysqli_stmt_execute($stmt)){
        exit('数据库操作失败！:'.mysqli_stmt_error($stmt));
    }
    $page = max(input('get','page','d'),1);
    header("Location:index.php?page=$page");
    exit();
}


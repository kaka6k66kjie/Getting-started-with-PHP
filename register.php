<?php
/**
 * Created by wangjie
 * Author: wangjie
 * Date: 2019/11/7
 * Time: 14:02
 */
session_start();
@$username = $_POST['username'];
@$password = $_POST['password'];

require './common/init.php';
require './common/function.php';

//接收register.html页面表单post过来的数据
$user = trim(input('post','username','s'));
$pass =  trim(input('post','password','s'));
//对密码进行md5加密
$pswd = md5($pass);

//检查数据库中是否存在这个用户，则注册失败 页面重新定位到注册页面
$che_user = "select * from user_table where username='$user'";
if(!$res_user=mysqli_query($link,$che_user)){
    exit("SQL[$che_user]预处理失败：".mysqli_error($link));
}else{
    $result = mysqli_fetch_all($res_user,MYSQLI_ASSOC);
    if($result){
        echo  '注册失败！您输入的用户名已被注册！<a href="/PHPProjects/view/html/register.html">如果您的浏览器没有自动跳转，请点击这里</a>' . '<script>
    setTimeout(function(){window.location.href=\'/PHPProjects/view/html/register.html\';},3000)</script>' ;
    }else{
        //如果没有被注册，则对这个注册密码进行md5加密，并且存到数据库中
        $User_sql = 'INSERT INTO  `user_table` (`username`,`password`) VALUES (?,?)';
        if(!$stmt = mysqli_prepare($link,$User_sql)){
            exit("SQL[$User_sql]预处理失败：".mysqli_error($link));
        }
        mysqli_stmt_bind_param($stmt,'ss',$user,$pswd);
        if(!mysqli_stmt_execute($stmt)){
            exit('数据库操作失败：'.mysqli_stmt_error($stmt));
        }
        echo  '注册成功！即将跳转到登录页！<a href="/PHPProjects/view/html/login.html">如果您的浏览器没有自动跳转，请点击这里</a>' . '<script>
    setTimeout(function(){window.location.href=\'/PHPProjects/view/html/login.html\';},3000)</script>' ;
    }
}

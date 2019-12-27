<?php
/**
 * Created by viruser
 * Author: viruser
 * Date: 2019/11/28
 * Time: 11:25
 **/

//开始session
session_start();

//接收login.html表单传过来的两个变量
$username =trim( $_POST ['username']);
$password = trim($_POST ['password']);

require './common/init.php';
require './common/function.php';
//查询数据库 查询到数据库中存储的用户名和密码
$pswd = md5($password);
//MD5加密之后和之前加密的用户密码对比，对比成功之后显示登录成功页面
$check_query ="select * from user_table where username = '$username' and password = '$pswd' limit 1";
if(!$res=mysqli_query($link,$check_query)){
    exit("SQL[$check_query]预处理失败：".mysqli_error($link));
}else {
    //登陆成功  保存数组对象
    $result = mysqli_fetch_all($res,MYSQLI_ASSOC);
    foreach ($result as $v){
//        遍历数组将里面user_id赋给一个变量
        $user_id = $v['user_id'];
    }
    //用session保存user_id
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    if($_SESSION['user_id']){
        echo  $username, ',登录成功！欢迎您！<a href="index.php">如果您的浏览器没有自动跳转，请点击这里</a>' . '<script>
    setTimeout(function(){window.location.href=\'index.php\';},3000)</script>' ;
    }else{
        echo  '登录失败！请重新输入正确的用户名和密码！<a href="index.php">如果您的浏览器没有自动跳转，请点击这里</a>' . '<script>
    setTimeout(function(){window.location.href=\'/PHPProjects/view/html/login.html\';},3000)</script>' ;
    }
}
$action = input('get','action','s');
if($action=='logout'){
    header('Location:index.php');
    exit();
}

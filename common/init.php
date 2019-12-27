<?php
/**
 * Created by wangjie
 * Author: wangjie
 * Date: 2019/11/7
 * Time: 14:01
 */
$link = mysqli_connect('localhost','root',null,'work');
//设置字符集
if(!$link){
    exit('数据库连接失败'.mysqli_connect_error());
}
mysqli_set_charset($link,'utf8');//成功返回true,失败返回false
//设置时区
date_default_timezone_set('Asia/Shanghai');

//设置mbstring扩展的内置编码
//mb_internal_encoding('UTF-8');
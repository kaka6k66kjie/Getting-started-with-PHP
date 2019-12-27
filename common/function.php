<?php
/**
 * Created by wangjie
 * Author: wangjie
 * Date: 2019/11/7
 * Time: 14:01
 */
//项目中的通用函数库，常用于封装一些常用代码，提高代码的复用性、可维护性等
/**
 * 接收输入函数
 * @param array $method 输入的数组（可用字符串get、post来表示）
 * @param string $name 从数组中提取出的变量名
 * @param string $type 表示类型的字符串
 * @param mixed $default 变量不存在时使用的默认值
 * @return mixed 返回的结果
 */
function input($method,$name,$type='s',$default=''){
    switch ($method){
        case 'get':$method = $_GET;break;
        case 'post':$method = $_POST;break;
    }
    $data = isset($method[$name])? $method[$name]:$default;
    switch ($type){
        //'s'表示字符串
        case 's':
            return is_string($data)? $data:$default;
            //‘d’表示整型
        case 'd':
            return (int)($data);
            //‘a’表示数组
        case 'a':
            return is_array($data)? $data:[];
        default:
            trigger_error('不存在的过滤类型"'.$type.'"');
    }
}
/**
 *  格式化日期
 * @param  type $time 给定时间戳
 * @param string 从给定时间到现在经过了多长时间（天/小时/分/秒）
 */
function format_date($time){
    $diff = time() - $time;
    $format = [8600=>'天',3600=>'小时',60=>'分钟',1=>'秒'];
    foreach ($format as $k =>$v){
        $result = floor($diff/$k);
        if($result){
            return $result.$v;
        }
    }
   return '0.5秒';
}
/**
 * 数据库的分页查询实现的原理是利用limit限制select语句查询出的数据
 * @param int $page 当前页码值
 * @param int $size 每页显示的条数
 * @return string 生成后的结果
 */
function page_sql($page, $size){
    return ($page-1)*$size.','.$size;
}
/**
 * 分页导航
 * @param string $url 连接地址
 * @param int $total 总记录数
 * @param int $page 当前页码值
 * @param int $size 每页显示的条数
 * @return string 生成的html结果
 */
function page_html($url,$total,$page,$size){
    //计算总页数
    $maxpage = max(ceil($total/$size),1);
    //如果不足两页，则不显示分页导航
    if($maxpage<=1){
        return '';
    }
    if($page ==1){
        $first = '<a  style="background: #dd0000">首页</a>';
        $prev = '<a>上页 </a>';
    }else{
        $first = "<a href=\"{$url}1\">首页</a>";
        $prev = '<a href="'.$url.($page-1).'"><<上页</a>';
    }
    if($page==$maxpage){
        $next = '<a >下页>></a>';
        $last = '<a style="background: #dd0000">尾页</a>';
    }else{
        $next = '<a href="'.$url.($page+1).'" >下页>></a>';
        $last = "<a href=\"{$url}{$maxpage}\">尾页</a>";
    }
    return " $first $prev $next $last";
}
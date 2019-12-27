function disp_confirm()
{
    var r=confirm("您确定要删除吗？")
    if (r==true)
    {
        return true
    } else {
        return false
    }
}
function delReplay()
{
    var r=confirm("您确定要删除吗？")
    if (r==true)
    {
        return true
    } else {
        return false
    }
}
function add() {
    var x = document.forms["myForm"]["Info_title"].value;
    var y = document.forms["myForm"]["Info_content"].value;
    if (x == "" || y.length<=6) {
        alert("标题不能为空或留言内容不能少于6个字符");
        return false;
    }
}
function a(){
    var y=document.getElementById('content').value;
    var cot=y.length;
    if(cot<6){
        document.getElementById('le').innerHTML="输入内容不能小于6个字符";
    }
    else{
        document.getElementById('le').innerHTML="你当前输入了"+cot+"字符";
    }
}


function aClick () {
    $.ajax({
        url:"/PHPProjects/index.php?action=replay&id=<?=$v['id']?>&page=<?=$page?>", //请求的url地址
        type:"GET", //请求方式
        success:function(req){
            //请求成功时处理
            window.location = "http://localhost/PHPProjects/view/html/replay.html";
        }
    });

}
//jquery ajax请求的标准写法
function test_Ajax()
{
    $.ajax({
        url:"/PHPProjects/index.php?action=replay&id=<?=$v['id']?>&page=<?=$page?>", //请求的url地址
        type:"GET", //请求方式
        beforeSend:function(){
            //请求前的处理
        },
        success:function(req){
            //请求成功时处理
        },
        complete:function(){
            //请求完成的处理
        },
        error:function(){
            //请求出错处理
        }
    });
}

function show(tag){
    var light=document.getElementById(tag);
    var fade=document.getElementById('fade');
    light.style.display='block';
    fade.style.display='block';
    var p = document.getElementsByClassName().innerHTML = sd;
    console.log();

}
function hide(tag){
    var light=document.getElementById(tag);
    var fade=document.getElementById('fade');
    light.style.display='none';
    fade.style.display='none';
}

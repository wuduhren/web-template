<?php
require('_lib/init.php');
session_destroy();
session_write_close();

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>登入</title>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/custom.css">
<style>
html,body {
    background: #F2F6FA;
}

#error_msg {
    display: none;
    color:#c55;
}

</style>
</head>

<body>

<div class="row mt-5">
    <div class="col-md-4 offset-md-4 col-10 offset-1 text-center">
        <h3 class=""><?echo $_SYSYEM_NAME;?></h3>

        <div class="bg-white border shadow-sm rounded p-3">
            <img  class="rounded-circle" src="img/128x128.png">

            <div class="input-group mb-1 mt-3">
                <div class="input-group-prepend"><span class="input-group-text">帳號</span></div>
                <input id="account" type="text" class="form-control">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend"><span class="input-group-text">密碼</span></div>
                <input id="password" type="password" class="form-control">
            </div>

            <div id="error_msg">錯誤</div>

            <button id="btn_login" class="form-control btn-info mt-3">登入</button>
        </div>

        <p class="has-text-grey mt-3">
            <a href="#">註冊</a> &nbsp;·&nbsp;
            <a href="#">忘記密碼</a> &nbsp;·&nbsp;
            <a href="#">協助</a>
        </p>
    </div>
</div>

<div id="loading">
    <div class="spinner">
        <div class="r1"></div><div class="r2"></div><div class="r3"></div><div class="r4"></div>
    </div>
</div>
</body>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/md5.min.js"></script>
<script src="js/util.js"></script>
<script>
$().ready(function(){
    function login() {
        call_web_api({
            // debug:true,
            url:'action/action_user.php',
            delay:200,
            data:{
                action:'login',
                account:$('#account').val(),
                password:md5($('#password').val()),
            },
            error:function(payload) {
                $('#error_msg').html(payload).show()
                $('#account').focus().select()
            }
        })
    }

    $('#account')
    .on('focus', function(){$(this).select()})
    .on('keypress', function(e){
        let code = (e.keyCode) ? e.keyCode : e.which
        if (code==13) login()
    })
    .focus()

    $('#password')
    .on('focus', function(){$(this).select()})
    .on('keypress', function(e){
        let code = (e.keyCode) ? e.keyCode : e.which
        if (code==13) login()
    })

    // init
    $('#btn_login').on('click', login)
    hide_loading(200)
})
</script>

</html>
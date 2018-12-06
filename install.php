<?php
require('_lib/init.php');
check_login('install');

?>
<!DOCTYPE html>
<html>
<head>
<title>新增管理員</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/custom.css">
<style>
#card {
	margin: 3rem auto;
	width: 27rem;
}

</style>
</head>
<body>
<div id="card" class="card">
	<h5 class="card-header">新增管理員</h5>
	<div class="card-body">
		<div class="input-group mb-3">
		    <div class="input-group-prepend">
		        <span class="input-group-text">帳號</span>
		    </div>
		    <input id="acc_input" type="text" class="form-control">
		</div>
		<div class="input-group mb-3">
		    <div class="input-group-prepend">
		        <span class="input-group-text">電郵</span>
		    </div>
		    <input id="email_input" type="text" class="form-control">
		</div>
		<div class="input-group mb-3">
		    <div class="input-group-prepend">
		        <span class="input-group-text">密碼</span>
		    </div>
		    <input id="pwd_input" type="password" class="form-control">
		</div>
		<div class="input-group mb-3">
		    <div class="input-group-prepend">
		        <span class="input-group-text">確認密碼</span>
		    </div>
		    <input id="pwd_confirm_input" type="password" class="form-control">
		</div>
		<div class="tiny_info">* 帳號需為4個字以上的小寫英文</div>
		<div class="tiny_info">* 密碼須為6個字以上, 小寫英文及數字的組合</div>
		<div class="tiny_info">* 新增成功後root帳號將被刪除</div>
	</div>
	<div class="card-footer" style="text-align: right; background-color: #fff;">
		<a class="btn btn-secondary" <?echo 'href="'.$_URL_LOGIN.'"';?>>返回</a>
		<button id="confirm" class="btn btn-primary">確定</button>
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
<script src="js/bootstrap-notify.min.js"></script>
<script src="js/md5.min.js"></script>
<script src="js/util.js"></script>
<script>
$().ready(function(){

	$('#confirm').click(function(){
		if ($('#pwd_input').val()!=$('#pwd_confirm_input').val()) {
			alert('密碼不一致')
			return
		}

		call_web_api({
		    url: 'action/action_user.php',
		    data: {
		    	action: 'install',
		    	account: $('#acc_input').val(),
		    	password: md5($('#pwd_input').val()),
		    	email: $('#email_input').val()
		    },
		    error: function(msg) {
		        alert(msg)
		    }
		})
	})

	// -----------------------------------------------------------------------------
	hide_loading(200)
})
</script>
</html>
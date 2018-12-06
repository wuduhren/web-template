<?php
require('_lib/init.php');
check_login();

?>
<!DOCTYPE html>
<html>
<head>
<title>修改個人資料</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/custom.css">
<style>
body {
	text-align: center;
}
#card {
    margin: 3rem auto;
    width: 27rem;
}

</style>
</head>
<body>
<?require_once('_navbar.php')?>
<div id="card" class="card">
    <h5 class="card-header">修改個人資料</h5>
    <div class="card-body">
        <div id="warning" class="alert alert-danger"></div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">現有密碼</span>
            </div>
            <input id="info_pwd_input" type="password" class="form-control">
        </div>
        <br>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">修改帳號</span>
            </div>
            <input id="info_acc_inpput" type="text" class="form-control">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">修改電郵</span>
            </div>
            <input id="info_email_input" type="text" class="form-control">
        </div>
        <br>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">修改密碼</span>
            </div>
            <input id="info_new_pwd_input" type="password" class="form-control">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">確認密碼</span>
            </div>
            <input id="info_confirm_pwd_input" type="password" class="form-control">
        </div>
        <div class="tiny_info">* 帳號需為4個字以上的小寫英文</div>
        <div class="tiny_info">* 密碼須為6個字以上, 小寫英文及數字的組合</div>
        <div class="tiny_info">* 修改資料後需要重新登入</div>
    </div>
    <div class="card-footer" style="text-align: right; background-color: #fff;">
        <button class="btn btn-secondary" onclick="go_back()">取消</button>
        <button id="set_info" class="btn btn-primary">確定</button>
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
	$('#set_info').click(function(){
		let account = $('#info_acc_inpput').val()
		let email = $('#info_email_input').val()
		let newPwd = $('#info_new_pwd_input').val()
		let confirmPwd = $('#info_confirm_pwd_input').val()
		let pwd = $('#info_pwd_input').val()

	    if (pwd==''){
	        show_warning('現有密碼為必要欄位')
	        return
	    }

		let data = {}
		data.action = 'set_info'
	    data.password = md5(pwd)
	    
		if ($.trim(account)!='') data.account = account
		if ($.trim(email)!='') data.email = email
		if ($.trim(newPwd)!='') {
			if (newPwd!=confirmPwd) {
				show_warning('密碼不一致')
				return
			}
			data.new_password = md5(newPwd)
		}

		call_web_api({
		    url: 'action/action_user.php',
		    data: data,
		    error: function(msg) {
		        show_warning(msg)
		    }
		})
	})

	//------------------------------------------------------------------------------
	hide_loading(200)
})


function go_back() {
    window.history.back();
}
</script>
</html>
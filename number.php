<?php
require('_lib/init.php');
check_login('read_number');

?>
<!DOCTYPE html>
<html>
<head>
<title>數字</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/custom.css">
<style>
body {
	text-align: center;
}
h1 {
	margin: 1rem auto;
}

</style>
</head>
<body>
<?require_once('_navbar.php')?>

<h1 id="number">0</h1>

<?if(check_permission('edit_number')){?>
<button id="add" class="btn btn-dark">+</button>
<button id="minus" class="btn btn-dark">-</button>
<?}?>

<?if(check_permission('remove_number')){?>
<button id="remove" class="btn btn-warning">AC</button>
<?}?>

<div id="loading">
    <div class="spinner">
        <div class="r1"></div><div class="r2"></div><div class="r3"></div><div class="r4"></div>
    </div>
</div>

</body>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-notify.min.js"></script>
<script src="js/util.js"></script>
<script>

$().ready(function(){
	$('#add').click(function(){
	    call_web_api({
	        url: 'action/action_number.php',
	        data: {
	        	action: 'add',
	        	number: $('#number').text()
	        },
	        success: function(number){
	            $('#number').text(number)
	            show_notify('操作成功')
	        }
	    })
	})

	$('#minus').click(function(){
	    call_web_api({
	        url: 'action/action_number.php',
	        data: {
	        	action: 'minus',
	        	number: $('#number').text()
	        },
	        success: function(number){
	            $('#number').text(number)
	            show_notify('操作成功')
	        }
	    })
	})

	$('#remove').click(function(){
	    call_web_api({
	        url: 'action/action_number.php',
	        data: {
	        	action: 'remove'
	        },
	        success: function(number){
	            $('#number').text(number)
	            show_notify('操作成功')
	        }
	    })
	})

	// -----------------------------------------------------------------------------
	hide_loading(200)
})

</script>
</html>
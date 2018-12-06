<?php
require('_lib/init.php');
check_login();

?>
<!DOCTYPE html>
<html>
<head>
<title>首頁</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/custom.css">
<style>
body {
	text-align: center;
}

</style>
</head>
<body>
<?require_once('_navbar.php')?>

<h1>首頁</h1>

<div id="loading">
    <div class="spinner">
        <div class="r1"></div><div class="r2"></div><div class="r3"></div><div class="r4"></div>
    </div>
</div>
</body>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/util.js"></script>
<script>
$().ready(function(){
	hide_loading(200)
})
</script>
</html>
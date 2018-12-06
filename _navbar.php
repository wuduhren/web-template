<?php 
$url = $_SERVER['REQUEST_URI'];

?>

<nav class="navbar navbar-expand-md navbar-dark">
    <a class="navbar-brand" href="<?echo $_URL_HOME;?>">
		<?echo $_SYSYEM_NAME;?>
		<span class="badge badge-secondary small" style="font-size:0.7rem"><?=$_VERSION?></span>
	</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_menu" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
    </button>

    <div id="navbar_menu" class="collapse navbar-collapse">
        <div class="navbar-nav mr-auto">
        	<a class="nav-item nav-link <?if (strpos($url, 'home.php')) {echo ('active');}?>" href="home.php">首頁</a>
			<?if(check_permission('read_number')){?>
			<a class="nav-item nav-link <?if (strpos($url, 'number.php')) {echo ('active');}?>" href="number.php">數字</a>
			<?}?>
			<?if(check_permission('read_user')){?>
			<a class="nav-item nav-link <?if (strpos($url, 'user_list.php')) {echo ('active');}?>" href="user_list.php">人員</a>
			<?}?>
    	</div>
		<div class="navbar-nav ml-auto">
			<a class="nav-item nav-link <?if (strpos($url, 'set_info.php')) {echo ('active');}?>" href="set_info.php">修改個人資料</a>
			<a class="nav-item nav-link <?if (strpos($url, 'index.php')) {echo ('active');}?>" href="index.php">
				登出(<?=$_SESSION['account']?>)
			</a>
		</div>
	</div>
</nav>
<?php
require('../_lib/init.php');
$action = get_ajax_action();

if ($action=='add')
	add();
else if ($action=='minus')
	minus();
else if ($action=='remove')
	remove();

//------------------------------------------------------------------------------
function add(){
	check_login('edit_number');
	$number = get_request('number');
	die_success($number+1);
}

function minus(){
	check_login('edit_number');
	$number = get_request('number');
	die_success($number-1);
}

function remove(){
	check_login('remove_number');
	die_success(0);
}

?>
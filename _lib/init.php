<?php
// -----------------------------------------------------------------------------
$_MAINTENANCE = false;

$_VERSION = '1.0.0';
$_SYSYEM_NAME = '網頁模版';

$_URL_LOGIN = 'index.php';
$_URL_HOME = 'home.php';
$_URL_EXPIRE = 'expire.html';
$_URL_MAINTENANCE = 'maintenance.html';

$_PERMISSION_LIST = [
    'read_user'=>[
        'name'=>'讀取使用者'
    ],
    'add_user'=>[
        'name'=>'新增使用者'
    ],
    'edit_user'=>[
        'name'=>'修改使用者資料'
    ],
    'read_number'=>[
        'name'=>'讀取數目'
    ],
    'edit_number'=>[
        'name'=>'新增(修改)數目'
    ],
    'remove_number'=>[
        'name'=>'移除數目'
    ]
];

$PAGE_SIZE = 10;

// -----------------------------------------------------------------------------
error_reporting(E_ALL);
mb_internal_encoding('UTF-8');

// -----------------------------------------------------------------------------
// 維護中
if ($_MAINTENANCE) {
    if (get_client_type()!='browser') die_maintenance();
    die(file_get_contents(dirname(__DIR__).'/'.$_URL_MAINTENANCE));    
}

// -----------------------------------------------------------------------------
// 資料庫
$_CONFIG['db'] = array(
    'dsn'=>'mysql:host=localhost;',
    'database'=>'web-template',
    'username'=>'xxxxx',
    'password'=>'xxxxx',
    'encoding'=>'utf8mb4',
    'presistent'=>false
);

if ($_SERVER['HTTP_HOST']=='127.0.0.1') {
    $_CONFIG['db']['dsn'] = 'mysql:host=127.0.0.1;';
}
else if ($_SERVER['HTTP_HOST']=='localhost') {
    $_CONFIG['db']['dsn'] = 'mysql:host=localhost;';
}

require(__DIR__.'/db.php');
require(__DIR__.'/session.php');

// -----------------------------------------------------------------------------
// get_client_type
function get_client_type() {
    static $var = '';
    if ($var=='') {
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) $var='browser';
        else {
            $var = strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
            if ($var!='xmlhttprequest' && $var!='app') $var='browser';
        }
    }
    return $var;
}

// -----------------------------------------------------------------------------
// get_ajax_action
function get_ajax_action() {
    if (!isset($_REQUEST['action'])) die_error('action not found');
    return $_REQUEST['action'];
}

// -----------------------------------------------------------------------------
// get_request
function get_request($name, $error_msg=null, $default_value=null) {
    if (isset($_REQUEST[$name]) && !empty($_REQUEST[$name])) return $_REQUEST[$name];
    if ($error_msg!=null) die_error($error_msg);
    if (isset($_REQUEST[$name])) return $_REQUEST[$name];
    return $default_value;
}

// -----------------------------------------------------------------------------
// die_payload
function die_payload($code, $data=null) {
    if (session_status()==PHP_SESSION_ACTIVE) session_write_close();
    if ($data===null) die(json_encode([$code], JSON_UNESCAPED_UNICODE));
    else die(json_encode([$code, $data], JSON_UNESCAPED_UNICODE));
}

// -----------------------------------------------------------------------------
// 回傳錯誤 (code=0)
function die_error($msg=null) {die_payload(0, $msg);}
// 回傳成功 (code=1)
function die_success($data=null) {die_payload(1, $data);}
// 回傳維護中 (code=2)
function die_maintenance($url=null) {die_payload(2, $url);}
// 回傳轉址 (code=3)
function die_redirect($url=null) {die_payload(3, $url);}

// -----------------------------------------------------------------------------
// 轉址
function redirect($url=null) {
    if (session_status()==PHP_SESSION_ACTIVE) session_write_close();
    global $_URL_LOGIN;
    if ($url==null) $url=$_URL_LOGIN;
    if (get_client_type()=='xmlhttprequest') die_redirect($url);
    header('Location: '.$url);
    die();
}

// -----------------------------------------------------------------------------
function now() {return date('Y-m-d H:i:s');}

// -----------------------------------------------------------------------------
function pr($v) {
    if (get_client_type()!='browser')  return var_dump($v);
    echo('<br><pre>');print_r($v);echo('</pre>');
}

//------------------------------------------------------------------------------
function check_login($permission=null) {
    global $_URL_EXPIRE, $_URL_LOGIN;
    if (!isset($_SESSION)) redirect($_URL_EXPIRE);
    if (!isset($_SESSION['uid'])) redirect($_URL_EXPIRE);
    if (isset($permission) && !check_permission($permission)) redirect($_URL_LOGIN);
    return;
}

function check_permission($permission){
    $user_permission = $_SESSION['permission'];

    if (in_array('root', $user_permission)) return true;

    if (!is_array($permission)) { $permission = explode(',', $permission); }
    foreach ($permission as $p) {
        if (!in_array($p, $user_permission)) return false;
    }
    return true;
}















?>
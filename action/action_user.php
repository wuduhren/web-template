<?php
require('../_lib/init.php');
$action = get_ajax_action();

if ($action=='login')
    login();
else if ($action=='query')
    query();
else if ($action=='create')
    create();
else if ($action=='update')
    update();
else if ($action=='install')
    install();
else if ($action=='set_info')
    set_info();

//------------------------------------------------------------------------------
function login(){
    global $_URL_HOME;

    $account = get_request('account', '帳號為必要欄位');
    $password = get_request('password', '密碼為必要欄位');

    $rs = db()->query('SELECT `id`, `account`, `password`, `status`, `permission` FROM `user` WHERE `account`=:account LIMIT 1', $account);

    if (count($rs->data)==0) die_error('帳號: '.$account.' 不存在');
    if ($rs->password!=$password) die_error('密碼錯誤');
    if ($rs->status!=1) die_error('帳號已停權, 請洽管理人員');

    $_SESSION['uid'] = $rs->id;
    $_SESSION['account'] = $rs->account;
    $_SESSION['permission'] = [];
    if (isset($rs->permission)) { $_SESSION['permission'] = explode(',', $rs->permission); }

    if ($rs->account=='root') redirect('install.php');
    redirect($_URL_HOME);
}

function query(){
    check_login('read_user');

    $page_no = get_request('page_no');
    $page_size = get_request('page_size');

    die_success(get_list($page_no));
}

function create(){
    check_login('add_user');

    $account = get_request('account', '帳號為必要欄位');
    $password = get_request('password', '密碼為必要欄位');
    $status = get_request('status');
    $permission = get_request('permission');
    $email = get_request('email');

    check_account($account);
    check_password($password);
    check_email($email);

    $id = db()->insert('user', [
        'account'=>$account,
        'password'=>$password,
        'permission'=>$permission,
        'email'=>$email,
        'status'=>$status
    ]);
    
    die_success(get_list(1));
}

function update(){
    check_login('edit_user');

    $id = get_request('id', '找不到ID');
    $account = get_request('account', '帳號為必要欄位');
    $password = get_request('password');
    $status = get_request('status');
    $permission = get_request('permission');
    $email = get_request('email');

    check_account($account, $id);
    if (isset($password)) check_password($password);
    check_email($email);

    $data = [
        'id'=>$id,
        'account'=>$account,
        'permission'=>$permission,
        'email'=>$email,
        'status'=>$status
    ];
    if (isset($password)) $data['password'] = $password;
    db()->update('user', 'id=:id', $data);

    $rs = db()->query('SELECT `id`, `account`, `status`, `permission`, `email` FROM `user` ORDER BY `status` DESC');
    die_success(get_list(1));
}

function install(){
    global $_PERMISSION_LIST;
    check_login('install');

    $account = get_request('account', '帳號為必要欄位');
    $password = get_request('password', '密碼為必要欄位');
    $email = get_request('email');

    check_account($account);
    check_password($password);
    check_email($email);

    //get all permission
    $permission = '';
    foreach ($_PERMISSION_LIST as $per=>$info) {
        $permission.=$per.',';
    }

    $id = db()->insert('user', [
        'account'=>$account,
        'password'=>$password,
        'permission'=>$permission,
        'email'=>$email,
        'status'=>1
    ]);

    db()->exec('DELETE FROM `user` WHERE id=:id AND account="root"', $_SESSION['uid']);

    redirect();
}

function set_info(){
    check_login();

    $password = get_request('password', '原密碼為必要欄位');
    $account = get_request('account');
    $email = get_request('email');
    $new_password = get_request('new_password');

    $rs = db()->query('SELECT `password` FROM `user` WHERE `id`=:id', $_SESSION['uid']);
    if ($rs->password!=$password) die_error('密碼錯誤');

    if (!isset($account) && !isset($email) && !isset($new_password)) die_success();

    $data['id'] = $_SESSION['uid'];

    if (isset($account)) {
        check_account($account, $_SESSION['uid']);
        $data['account'] = $account;
    }
    if (isset($email)) {
        check_email($email);
        $data['email'] = $email;
    }
    if (isset($new_password)) {
        check_password($new_password);
        $data['password'] = $new_password;
    }

    db()->update('user', 'id=:id', $data);
    redirect();
}

//------------------------------------------------------------------------------
function check_account($account, $update_id=null){
    $limitation = '帳號需為4個字以上的小寫英文.';
    if (!ctype_lower($account)) die_error($limitation);
    if (strlen($account)<4) die_error($limitation);

    if ($account=='root' || $account=='admin') die_error('帳號無法使用保留名稱.');

    if ($update_id) {
        // account no change then no need to check
        $rs = db()->query('SELECT `account` FROM `user` WHERE `id`=:id', $update_id);
        if ($account==$rs->account) return;
    }

    $rs = db()->query('SELECT EXISTS(SELECT 1 FROM `user` WHERE `account`=:account)', $account);
    if (reset($rs[0])) die_error('帳號已存在');
}
function check_password($password){
    $limitation = '密碼須為6個字以上, 小寫英文及數字的組合.';
    if (!ctype_alnum($password)) die_error($limitation);//只可以有英文及數字
    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) die_error($limitation);//需同時包含英文及數字
    if (preg_match('/[A-Z]/', $password)) die_error($limitation);//不包含英文大寫
    if (strlen($password)<6) die_error($limitation);
    return;
}
function check_email($email){
    if ($email=='') return;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) die_error('電郵有誤.');
    return;
}

//------------------------------------------------------------------------------
function add_permission($uid, $p) {
    $rs = db()->query('SELECT `permission` FROM `user` WHERE `id`=:id LIMIT 1', $uid);
    $permission = explode(',', $rs->permission);

    if (!is_array($p)) { $p = explode(',', $p); }
    $permission = array_unique(array_merge($permission, $p));

    db()->update('user', 'id=:id',[
        'id'=>$uid,
        'permission'=>implode(',', $permission)
    ]);
}

function remove_permission($uid, $p) {
    $rs = db()->query('SELECT `permission` FROM `user` WHERE `id`=:id LIMIT 1', $uid);
    $permission = explode(',', $rs->permission);

    if (!is_array($p)) { $p = explode(',', $p); }
    $permission = array_diff($permission, $p);

    db()->update('user', 'id=:id',[
        'id'=>$uid,
        'permission'=>implode(',', $permission)
    ]);
}

//------------------------------------------------------------------------------
function get_list($page_no){
    global $PAGE_SIZE;
    
    $rs = db()->paging($PAGE_SIZE, $page_no)->query('SELECT `id`, `account`, `status`, `permission`, `email` FROM `user` ORDER BY `status` DESC');
    return [
        'list'=>$rs->data,
        'pager'=>$rs->pager(),
    ];
}
























?>
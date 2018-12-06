# Overview
Many small companies or start-up need a web interface for internal usage. For example, managing data, view statistic.  
So, here you are! A simple web template comes with

* Login, logout basic access control
* Example of ajax action accessing MySQL DB
* Add and manage system user
* Simple permission-based access control

With highly maintainable code built with jQuery and Bootstrap.

# Structure
## General
1. All directory comes with `index.html`, so it cannot be access accidentally.
2. Directory with `_` at the beginnings are private directory. It cannot be access directly (NGINX).
3. `robot.txt` prevents search engine from accessing.
4. `home.php` is an empty home page.
5. `number.php` is a simple example on how ajax action in `action/`.
6. `expire.html` will be redirect to when SESSION is expired.
7. `maintenance.html` will be redirect to when is expired.
8. `set_info.php` for user to change their info. 
9. `_doc/` for document, some sql file or log.
10. `install.php` for installation. See the Install section.

## _lib/
1. `db.php`, DB accessing functions.
2. `session.php`, for php session.
3. `init.php`, for configuration and important functions.

## action/
1. php action that will be called by ajax.
2. file naming: `action_xxxxx.php`, xxxxx is usually object that is being managed. For example, user, number, user.

## init.php
1. `$_MAINTENANCE`. If set to `true` , system would be redirect to `maintenance.html`.
2. `$_SYSYEM_NAME`. System name / project name.
3. `$_CONFIG['db']`. DB configuration.
4. `get_ajax_action()` for getting `$_REQUEST['action']`. See usage in example, `action/action_number.php`.
5. `get_request()` for getting parameters in ajax. If `$error_msg` is not set, the parameter will be optional.
6. `die_payload()` configure basic protocal for `die_error()`, `die_success()`, `die_maintenance ()`, `die_redirect ()` that returning ajax request. Front-end will react differently correspond to each kind of return. See `js/util.js` `call_web_api()`.

	```
	we return an array to the front-end
	[code, data]
	code is an integer
		0, error
		1, success
		2, maintenance
		3, redirect
	data is a string or any data structure that can be JSON Encode. 
	```
7. `pr()` is equivalent to print. It will print data by echo to brower. There is also a `pr()` in Front-end. See `js/util.js` `pr()`.
8. `show_warning()`, show warning on the `#warning` element. Usually used when ajax return error.
9. `show_notify()`, show a temporary notification box. You can use it when ajax successfully update somthing. Or anytime you want.
10. `check_login()`. This function varifies SESSION, which is set when loggin. If the SESSION does not exist or not correct, we will be redirected to login page.  
This function can also check if user is qualify by the permission(s). This parameter can be strings seperated by comma or an array of string. For example:

	```
	#check if user login
	check_login();
	#check if user login. Have permission of both read_number and edit_number
	check_login('read_number,edit_number'); #or
	check_login(['read_number', 'edit_number']);
	```
11. `check_permission()`. This function check if user has permission(s) and return a boolean. Parameter usage is simular to `check_login ()`.  
This is is particularly useful whem we want to controll front-end display by permission. For example, if user does not have the permission of `remove_number`, then we don't want to display the remove button.

	```
	<?if(check_permission('remove_number')){?>
	<button id="remove" class="btn btn-warning">Remove Number</button>
	<?}?>
	```
# Screen Shot
登入
<br/>
![](https://imgur.com/UK4B00x.png)
<br/>
<br/>
首頁
<br/>
![](https://imgur.com/UNY41ay.png)
<br/>
<br/>
人員管理
<br/>
![](https://imgur.com/9Fx2Lw9.png)
<br/>
<br/>
新增人員
<br/>
![](https://imgur.com/DYiS2la.png)
<br/>
<br/>
修改個人資料
<br/>
![](https://imgur.com/TbkJN29.png)


# Install
1. Setup `init.php`.
2. Put the whole file on your `www/`.
3. Install the sql file to DB.
4. You are able to login by a set of default account and password.

	```
	#account, this account has a special permission, install.
	root
	#password
	account+12345
	```
	
	Once you login with that account. You are forced to create a new account with all permission. Then the root will be automatically deleted.

# 套件
## Bootstrap(4.1.3)
1. 包含css及js
2. 都使用CDN

## jQuery(3.3.1)
1. 在js部分載入
2. 使用其CDN

## Bootstrap Notify(3.1.3)
<http://bootstrap-notify.remabledesigns.com/>

## JavaScript-MD5
<https://github.com/blueimp/JavaScript-MD5>



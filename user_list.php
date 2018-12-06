<?php
require('_lib/init.php');
check_login('read_user');

?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>人員</title>
</head>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/custom.css">

<style>
#user_list {
    margin: 2rem auto 0rem auto;
    width: 70%;
}

#permission_list {
    border-top: 1px solid #e9ecef;
    margin-top: 0.25rem;
    padding-top: 0.25rem;
}
.permission_checkbox {
    text-align: left;
    margin-top: 0.5rem;
    margin-left: 0.2rem;
}

.mb-3 {
    margin-bottom: 0.5rem !important;
}
</style>
<body>
<?require_once('_navbar.php')?>

<div style="text-align: center;">
    <?if(check_permission('add_user')){?>
    <button id="add_user" class="btn btn-secondary">新增人員</button>
    <?}?>
</div>

<table id="user_list" class="table table-hover">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">帳號</th>
            <th scope="col">權限</th>
            <th scope="col">狀態</th>
        </tr>
    </thead>
    <tbody id="user_tbody"></tbody>
</table>
<div id="pager" class="pager"></div>

<div id="user_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="user_modal" aria-hidden="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="user_modal_title" class="modal-title">修改資料</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div>
                    <div id="warning" class="alert alert-danger"></div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">帳號</span>
                        </div>
                        <input id="acc_inpput" type="text" class="form-control">
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
                </div>

                <div id="permission_list"></div>

                <div class="permission_checkbox form-check">
                    <input id="status" type="checkbox" class="form-check-input">
                    <label class="form-check-label" for="status">啟用</label>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">取消</button>
                <?if(check_permission('edit_user')){?>
                <button id="save" class="btn btn-primary">確定</button>
                <?}?>
            </div>
        </div>
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
let permission_list = <?=json_encode($_PERMISSION_LIST)?>;
let uid = null;

$().ready(function(){
    $('#add_user').click(function(){
        clear_modal()
        $('#user_modal_title').text('新增人員')
        $('#status').prop('checked', true)
        uid = null

        hide_warning()
        $('#user_modal').modal()
    })

    $('#user_tbody').on('click', 'tr', function(){
        clear_modal()
        $('#user_modal_title').text('修改資料')
        uid = $(this).attr('data-uid')

        $('#acc_inpput').val($(this).attr('data-account'))
        $('#email_input').val($(this).attr('data-email'))
        if ($(this).attr('data-status')==1) { $('#status').prop('checked', true) }
        prepare_permission($(this).attr('data-permission'))

        hide_warning()
        $('#user_modal').modal()
    })

    $('#save').click(function(){
        let data = {}

        if (uid) {
            data.action = 'update'
            data.id = uid
        } else {
            data.action = 'create'
        }

        data.account = $('#acc_inpput').val()
        data.email = $('#email_input').val()
        data.permission = get_permission()

        let status = 0
        if ($('#status').is(':checked')) { status=1 }
        data.status = status

        let password = $('#pwd_input').val()
        if (password!=''){
            if (password!=$('#pwd_confirm_input').val()) {
                $('#pwd_input').val('')
                $('#pwd_confirm_input').val('')
                show_notify('密碼不一致')
                return
            }
            data.password = md5(password)
        }

        call_web_api({
            url: 'action/action_user.php',
            data: data,
            success: function(user_data){
                render_user_list(user_data)
                $('#user_modal').modal('hide')
            },
            error: function(msg) {
                show_warning(msg)
            }
        })
    })

    // -----------------------------------------------------------------------------
    get_user_list()
    render_permission_list(permission_list)
    hide_loading(200)
})

function render_user_list(user_data, scroll_to_top){
    $('#user_tbody').empty()

    for (let record of user_data.list) {
        let tr = $('<tr>')
        let status = ''
        let permission = ''

        if (record.status==0) {
            status = '停權'
        } else if (record.status==1) {
            status = '啟用'
        }

        tr.attr('data-uid', record.id)
        .attr('data-account', record.account)
        .attr('data-email', record.email)
        .attr('data-status', record.status)
        .attr('data-permission', record.permission)
        .append($('<td>').html(record.id))
        .append($('<td>').html(record.account))
        .append($('<td>').html(get_permission_name(record.permission)))
        .append($('<td>').html(status))

        $('#user_tbody').append(tr)
    }

    render_pager('#pager', user_data.pager, get_user_list)

    if (scroll_to_top==true) window.scrollTo(0,0)
}

function get_user_list(opt) {
    opt = opt || {}

    call_web_api({
        url:'action/action_user.php',
        data:{
            action:'query',
            page_no:opt.page_no,
        },
        success:function(data){
            render_user_list(data, opt.to_top)
        },
        error:function(data){pr(data)}
    })
}

function clear_modal(){
    $('#acc_inpput').val('')
    $('#email_input').val('')
    $('#pwd_input').val('')
    $('#pwd_confirm_input').val('')
    $('#status').prop('checked', false)
    clear_permission()
}

// -----------------------------------------------------------------------------
// permission
function render_permission_list(permission_list){
    $('#permission_list').empty()
    let list = $('#permission_list')

    let checkbox = '<div class="permission_checkbox form-check">'+
        '<input id="{{id}}" type="checkbox" class="form-check-input">'+
        '<label class="form-check-label" for="{{id}}">{{name}}</label>' +
    '</div>';

    for (let per in permission_list) {
        let name = permission_list[per]['name']
        list.append(checkbox.replace(/{{id}}/g, get_dom_id(per)).replace(/{{name}}/g, name))
    }
}

function get_permission_name(user_permission){
    let name = ''
    for (let per of user_permission.split(',')) {
        if (permission_list[per] && permission_list[per]['name']) name+=(permission_list[per]['name']+', ')
    }
    return name.slice(0, -2) //remove last comma and space
}
function get_permission(){
    let permission = ''
    for (let per in permission_list) {
        if ($('#'+get_dom_id(per)).is(':checked')) { permission+=per+',' }
    }
    return permission
}
function prepare_permission(user_permission){
    for (let per of user_permission.split(',')) {
        $('#'+get_dom_id(per)).prop('checked', true)
    }
}
function clear_permission(){
    for (let per in permission_list) {
        $('#'+get_dom_id(per)).prop('checked', false)
    }
}
function get_dom_id(per){
    return 'if_'+per
}


</script>
</html>
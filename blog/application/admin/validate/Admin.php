<?php

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate{
    protected $rule = [
        'admin_username'=>'require',
        'admin_password'=>'require',
    ];
    protected $message = [
        'admin_username.require'=>'用户名不能为空！',
        'admin_password.require'=>'密码不能为空！',
    ];
}
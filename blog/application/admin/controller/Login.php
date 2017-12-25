<?php

namespace app\admin\controller;

use app\common\model\Admin;
use houdunwang\crypt\Crypt;
use think\Controller;

class Login extends Controller
{
    //
    public function login(){
        //echo Crypt::encrypt('admin888');//加密结果  h3vPU8JGuF3VS/uxIpjRSw==
       // echo Crypt::decrypt('h3vPU8JGuF3VS/uxIpjRSw==');  //解密 admin888
        //测试连接数据库
        //$data=db('admin')->find(2);
        //dump($data);
        if(request()->isPost()){
            $res = (new Admin())->login(input('post.'));
            if ($res['valid']){
                //说明登陆成功
                $this->success($res['msg'],'admin/entry/index');exit;
            }
            else{
                //登陆失败
                $this->error($res['msg']);exit;
            }
        }
        //加载登录页面
        return $this->fetch();
    }
}

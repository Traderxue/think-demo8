<?php

namespace app\controller;

use think\Request;
use app\model\Admin as AdminModel;
use app\BaseController;
use app\util\Res;

class Admin extends BaseController
{
    protected $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function register(Request $request){
        $username = $request->post("usernmae");
        $password =password_hash($request->post("password"),PASSWORD_DEFAULT);

        $a = AdminModel::where('username',$username)->find();
        
        if($a){
            return $this->result->error("用户已存在,注册失败");
        }

        $admin = new AdminModel([
            "username" =>$username,
            "password"=>$password
        ]);
        $res = $admin->save();
        if($res){
            return $this->result->success("注册成功",$admin);
        }
        return $this->result->error("注册失败");
    }


    public function login(Request $request)
    {
        $username = $request->post("username");
        $password = $request->post('password');
        
    }
}

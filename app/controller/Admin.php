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
        $username = $request->post("username");
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

        $admin = AdminModel::where("username",$username)->find();

        if($admin==null){
            return $this->result->error("用户不存在");
        }
        if(password_verify($password,$admin->password)){
            return $this->result->success("登录成功",$admin);
        }
        return $this->result->error("登录失败");
    }

    public function page(Request $request){
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        $username = $request->param("username");
        $list = AdminModel::where("username","like","%{$username}%")->paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);

        return $this->result->success("获取数据成功",$list);
    }
}

<?php

namespace app\controller;

use think\Request;
use app\model\User as UserModel;
use app\util\Res;
use app\BaseController;

class User extends BaseController
{
    private $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function register(Request $request)
    {
        $username = $request->post("username");
        $password = password_hash($request->post("password"), PASSWORD_DEFAULT);

        $u = UserModel::where("username", $username)->find();

        if ($u) {
            return $this->result->error("注册失败,用户已存在");
        }

        $user = new UserModel([
            "username" => $username,
            "password" => $password
        ]);
        $res = $user->save();

        if ($res) {
            return $this->result->success("注册成功", $user);
        }
        return $this->result->error("注册失败");
    }

    public function login(Request $request)
    {
        $username = $request->post("username");
        $password = $request->post("password");

        $user = UserModel::where("username", $username)->where("disabled", 0)->find();

        if ($user == null) {
            return $this->result->error("登录失败,用户不存在");
        }
        if (password_verify($password, $user->password)) {
            return $this->result->success("登录成功", $user);
        }

        return $this->result->error("登录失败");
    }

    public function page(Request $request)
    {
        $page = $request->param("page", 1);
        $pageSize = $request->param("pageSize", 10);
        $name = $request->param('name');

        $list = UserModel::where("name", "like", "%{$name}%")->paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);

        return $this->result->success("获取数据成功", $list);
    }

    public function getByUserName(Request $request)
    {
        $username = $request->param("username");
        $user = UserModel::where("username", $username)->find();
        if ($user) {
            return $this->result->success("获取数据成功", $user);
        }
        return $this->result->error("获取数据失败");
    }

    public function disabled($id)
    {
        $user = UserModel::where("id", $id)->find();

        $res = $user->save([
            "disabled" => 1
        ]);
        if ($res) {
            return $this->result->success("禁用用户成功", $res);
        }
    }

    public function verify(Request $request)
    {
        $id = $request->post("id");
        $name = $request->post("name");
        $phone = $request->post("phone");
        $id_card = $request->post("id_card");

        $user = UserModel::where("id", $id)->find();

        $res = $user->save([
            "name" => $name,
            "phone" => $phone,
            "id_card" => $id_card
        ]);

        if ($res) {
            return $this->result->success("已提交认证,等待审核", $res);
        }
        return $this->result->error("提交失败");
    }

    public function pass(Request $request)
    {
        $id = $request->post("id");
        $user = UserModel::where("id", $id)->find();
        $res = $user->save([
            "verify" => 1
        ]);
        if ($res) {
            return $this->result->success("已审核通过", $user);
        }
        return $this->result->error("已取消通过");
    }

    public function deleteById($id)
    {
        $res = UserModel::where("id", $id)->delete();

        if ($res) {
            return $this->result->success("删除成功", $res);
        }
        return $this->result->error("删除失败");
    }

    public function collect($id){
        $list = UserModel::where("id",$id)->field("type")->select();
        return $this->result->success("获取数据成功",$list);
    }
}

<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\util\Res;
use app\model\User as UserModel;
use app\model\Sign as SignModel;

class Sign extends BaseController{
    private $result;

    public function __construct(\think\App $app){
        $this->result = new Res();
    }

    public function add(Request $request){
        $postData = $request->post();

        $user = UserModel::where("id",$postData["u_id"])->find();

        $user->save([
            "balance"=>(float) $user->balance+ (float) $postData["amount"]
        ]);

        $sign = new SignModel([
            "u_id"=>$postData["u_id"],
            "sign_date"=>date("Y-m-d H:i:s"),
            "sign"=>1
        ]);
        $res = $sign->save();
        if(!$res){
            return $this->result->error("签到失败");
        }
        return $this->result->success("签到成功",$sign);
    }

    public function getByDate($date){
        $sign = UserModel::where("date",$date)->find();
        if($sign==null){
            return $this->result->error("获取数据失败");
        }
        return $this->result->success("获取数据成功",$sign);
    }
}
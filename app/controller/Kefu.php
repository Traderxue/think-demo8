<?php

namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Kefu as KefuModel;
use app\util\Res;

class Kefu extends BaseController
{
    private $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function edit(Request $request)
    {
        $postData = $request->post();
        $kefu = KefuModel::where("id", 1)->find();
        $res = $kefu->save([
            "url" => $postData["url"],
            "add_time" => date("Y-m-d H:i:s")
        ]);
        if ($res) {
            return $this->result->success("数据编辑成功", $kefu);
        }
        return $this->result->error("编辑数据失败");
    }
}

<?php

namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Position as PositionModel;
use app\model\User as UserModel;
use app\util\Res;

class Position extends BaseController
{
    private $result;
    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function open(Request $request)
    {
        $postData = $request->post();

        $position = new PositionModel([
            "u_id" => $postData["u_id"],
            "type" => $postData["type"],
            "buy_price" => $postData["buy_price"],
            "amount" => $postData["amount"],
            "fee" => (float) $postData["amount"] * 0.001,
            "direction" => $postData["direction"],
            "time" => date("Y-m-d H:i:s")
        ]);

        $res = $position->save();
        if ($res) {
            return $this->result->success("开仓成功", $position);
        }
        return $this->result->error("开仓失败");
    }

    public function close(Request $request)
    {
        $postData = $request->post();


        $position = PositionModel::where("id", $postData["id"])->find();

        $user = UserModel::where("id", $position->u_id)->find();

        if ($position->direction == '1') {
            //做多
            $r = (float) $postData["close_price"] - (float) $position->buy_price;

            $user->save([
                "balance" => (float) $user->balance + $r
            ]);

            $res = $position->save([
                "close_price" => $postData["close_price"],
                "close" => 1,
                "result" => $r
            ]);
            if ($res) {
                return $this->result->success("平仓成功", $position);
            }
            return $this->result->error("平仓失败");
        } else {
            //做空
            $r = (float) $position->buy_price - (float) $postData["close_price"];
            $user->save([
                "balance" => (float) $user->balance + $r
            ]);

            $res = $position->save([
                "close_price" => $postData["close_price"],
                "close" => 1,
                "result" => $r
            ]);
            if ($res) {
                return $this->result->success("平仓成功", $position);
            }
            return $this->result->error("平仓失败");
        }
    }

    public function page(Request $request)
    {
        $page = $request->param("page", 1);
        $pageSize = $request->param("pageSize", 10);
        $type = $request->param("type");

        $list = PositionModel::where("type", "like", "%{$type}%")->paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);

        return $this->result->success("获取数据成功", $list);
    }

    public function getByUid($id)
    {
        $list = PositionModel::where("id", $id)->select();
        return $this->result->success("获取数据成功", $list);
    }

    public function deleteById($id)
    {
        $res = PositionModel::where("id", $id)->delete();
        if ($res) {
            return $this->result->success("删除数据成功", $res);
        }
        return $this->result->error("删除数据失败");
    }
}

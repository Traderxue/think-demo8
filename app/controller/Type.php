<?php

namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Type as TypeModel;
use app\util\Res;

class Type extends BaseController
{
    protected $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function add(Request $request)
    {
        $postData = $request->post();

        $t = TypeModel::where("type",$postData["type"])->find();

        if($t){
            return $this->result->error("币种已存在");
        }

        $type = new TypeModel([
            "type" => $postData["type"],
            "add_time" => date("Y-m-d H:i:s")
        ]);

        $res = $type->save();
        if ($res) {
            return $this->result->success("添加数据成功", $type);
        }
        return $this->result->error("添加数据失败");
    }

    public function page(Request $request)
    {
        $page = $request->param("page", 1);
        $pageSize = $request->param("pageSize", 10);
        $type = $request->param("type");

        $list = TypeModel::where("type", "like", "%{$type}%")->paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);
        return $this->result->success("获取数据成功", $list);
    }

    public function delete($id){
        $res = TypeModel::where("id",$id)->delete();
        if($res){
            return $this->result->success("删除数据成功",$res);
        }
        return $this->result->error("删除数据失败");
    }   

}

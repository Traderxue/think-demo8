<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Kefu as KefuModel;
use app\util\Res;

class Kefu extends BaseController{
    private $result;

    public function __construct(\think\App $app){
        $this->result = new Res();
    }
    
}
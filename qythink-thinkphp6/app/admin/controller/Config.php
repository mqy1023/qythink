<?php

namespace app\admin\controller;


use app\BaseController;
use app\common\model\ConfigModel;

class Config extends BaseController
{
    //修改系统配置
    public function editConfig()
    {
        $ConfigModel = new ConfigModel();
        $type = input("type");
        if ($this->request->isPost()) {
            $data = [];
            if ($type == 1) {
                //接收参数
                $data['website'] = input("website");
                $data['http'] = input("http");
                $data['icp'] = input("icp");
                $data['qq'] = input("qq");
                $data['email'] = input("email");
                $data['phone'] = input("phone");
                $data['com_name'] = input("com_name");
                $data['com_address'] = input("com_address");
                $data['keyword'] = input("keyword");
                $data['describe'] = input("describe");
                $data['com_intro'] = input("com_intro");

            } elseif ($type == 2) {
                $data['wx_name'] = input("wx_name");
                $data['number'] = input("number");
                $data['appid'] = input("appid");
                $data['appsecret'] = input("appsecret");
                $data['token_url'] = input("token_url");
                $data['encoding_aeskey'] = input("encoding_aeskey");
                $data['wx_img'] = input("wx_img");
            } elseif ($type == 3) {
                $data['provider'] = input("provider");
                $data['accesskey'] = input("accesskey");
                $data['secretkey'] = input("secretkey");
                $data['endpoint'] = input("endpoint");
            } else {
                return json(return_msg("参数不正确"));
            }
            $res = $ConfigModel->editConfig($data);
            return json($res);
        }
    }

    public function getSysConfig(){
        $ConfigModel = new ConfigModel();
        $res = $ConfigModel->getSysConfig();
        return json($res);
    }

}
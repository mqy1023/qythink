<?php

namespace app\admin\controller;


use app\common\model\SysDictDetailModel;
use app\common\model\SysDictModel;

class SysDict extends Base
{
    //列表字典
    public function lstDict()
    {
        $SysDictModel = new SysDictModel();

        //接收变量
        $data['search_text'] = input("search_text");
        $list = $SysDictModel->lstDict($data);
        return json($list);

    }

    //添加修改字典
    public function addEditDict($op = 'add')
    {
        $SysDictModel = new SysDictModel();
        $id = input("id");
        if ($this->request->isPost()) {
            //接收参数
            $data['title'] = input("title");  //
            $data['dict_code'] = input("dict_code");  //
            $data['remark'] = input("remark");  //
            $data['seq'] = input("seq");  //
            $res = $SysDictModel->addEditDict($data, $op, $id);
            return json($res);
        }
        $info = [];
        return json($info);
    }

    //删除字典
    public function delDict()
    {
        if ($this->request->isPost()) {
            $SysDictModel = new SysDictModel();
            $id = input("id");
            $res = $SysDictModel->delDict($id);
            return json($res);
        }
    }


    //列表数据
    public function lstDictDetail()
    {
        $SysDictDetailModel = new SysDictDetailModel();
        //接收变量
        $data['search_text'] = input("search_text");
        $data['dict_code'] = input("dict_code");
        $data['status'] = input("status");
        $list = $SysDictDetailModel->lstDictDetail($data);
        return json($list);

    }

    //添加修改数据
    public function addEditDictDetail($op = 'add')
    {
        $SysDictDetailModel = new SysDictDetailModel();
        $id = input("id");
        if ($this->request->isPost()) {
            //接收参数
            $data['dict_code'] = input("dict_code");  //
            $data['name'] = input("name");  //
            $data['code'] = input("code");  //
            $data['extra_code'] = input("extra_code");  //
            $data['remark'] = input("remark");  //
            $data['seq'] = input("seq");  //
            $data['status'] = input("status");  //
            $res = $SysDictDetailModel->addEditDictDetail($data, $op, $id);
            return json($res);
        }
        $info = [];
        return json($info);
    }

    //删除数据
    public function delDictDetail()
    {
        if ($this->request->isPost()) {
            $SysDictDetailModel = new SysDictDetailModel();
            $id = input("id");
            $res = $SysDictDetailModel->delDictDetail($id);
            return json($res);
        }
    }


}
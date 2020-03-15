<?php

namespace app\admin\controller;


use app\BaseController;
use app\common\model\SysRoleModel;

class SysRole extends BaseController
{
    //列表
    public function lst($op = 'page')
    {
        $SysRoleModel = new SysRoleModel();
        if ($op == 'page') {
            //接收变量
            $data['search_text'] = input("search_text");
            $list = $SysRoleModel->lst($data);
            return json($list);
        } else {
            //搜索参数
            $param = [];
            return json($param);
        }
    }

    //添加修改
    public function addEdit($op = 'add')
    {
        $SysRoleModel = new SysRoleModel();
        $id = input("id");
        if ($this->request->isPost()) {
            //接收参数
            $data['name'] = input("name");  //权限组名
            $data['remark'] = input("remark");  //备注
            $res = $SysRoleModel->addEdit($data, $op, $id);
            return json($res);
        }
        $info = [];
        return json($info);
    }

    //删除
    public function del()
    {
        if ($this->request->isPost()) {
            $SysRoleModel = new SysRoleModel();
            $id = input("id");
            $res = $SysRoleModel->del($id);
            return json($res);
        }
    }

    //编辑菜单权限
    public function editMenu()
    {
        $SysRoleModel = new SysRoleModel();
        $id = input("id");
        if ($this->request->isPost()) {
            //接收参数
            $data['role_ids'] = input("role_ids");  //菜单权限
            $res = $SysRoleModel->editMenu($data, $id);
            return json($res);
        }
        $info = $SysRoleModel->getEditMenu($id);
        return json($info);
    }

}
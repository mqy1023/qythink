<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\model\SysRoleModel;
use app\common\model\SysUserModel;

class SysUser extends BaseController
{
    //列表
    public function lst($op = 'page')
    {
        $SysUserModel =new SysUserModel();
        if ($op == 'page') {
            //接收变量
            $data['search_text'] = input("search_text");
            $list = $SysUserModel->lst($data);
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
        $SysUserModel = new SysUserModel();
        $id = input("id");
        if ($this->request->isPost()) {
            //接收参数
            $data['name'] = input("name");
            $data['account'] = input("account");
            $data['role_id'] = input("role_id");
            $data['mobile'] = input("mobile");
            $data['email'] = input("email");
            $data['sex'] = input("sex");
            $data['status'] = input("status");
            $data['head_img'] = input("head_img");
            $data['depart_id'] = input("depart_id");
            $password = input("password");
            $password2 = input("password2");
            $is_pwd = input("is_pwd");

            if ($op == 'add' || $is_pwd == '1') {
                if ($password != $password2) {
                    return json(return_msg("密码不一致"));
                }
                $data['password'] = md5($password.config('qythink.halt'));
            }
            
            if(empty($data['role_id'])){
                return json(return_msg("请选择角色"));
            }


            $res = $SysUserModel->addEdit($data, $op, $id);
            return json($res);
        }
        $SysRoleModel = new SysRoleModel();
        $info['role'] = $SysRoleModel->getAllRole();
        return json($info);
    }

    //删除
    public function del()
    {
        if ($this->request->isPost()) {
            $SysUserModel =new SysUserModel();
            $id = input("id");
            $res = $SysUserModel->del($id);
            return json($res);
        }
    }

}
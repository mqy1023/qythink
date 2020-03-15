<?php
namespace app\index\controller;


use app\BaseController;
use app\common\model\SysMenuModel;
use app\common\model\SysUserModel;
use think\facade\Cache;

class Login extends BaseController
{
    /*
     * 登录界面
     * */
    public function login()
    {

        if ($this->request->isPost()) {
            $data['account'] = input("account");
            $password = input("password");
            if (empty($data['account'])) {
                return json(['status' => false, 'msg' => '账号不能为空', 'data' => '']);
            }
            if (empty($password)) {
                return json(['status' => false, 'msg' => '密码不能为空', 'data' => '']);
            }
            //base64解密
//            $password = base64_decode($password);
//            $data['account'] = base64_decode($data['account']);
            //md5加密
            $data['password'] = md5($password.config('qythink.halt'));
            $SysUserModel = new SysUserModel();
            $res = $SysUserModel->login($data);
            return json($res);
        }
    }
    //获取登录用户信息
    public function getUserInfo(){
        $SysUserModel = new SysUserModel();
        $res = $SysUserModel->getUserInfo();
        return json($res);
    }

    //获取登录用户的菜单信息
    public function getMenu(){
       $SysMenuModel=new SysMenuModel();
        $res = $SysMenuModel->getMenu();
        return json($res);
    }


    // 退出, 前端做处理
    public function logout(){
        return json(return_msg("success", true,[]));
    }

}
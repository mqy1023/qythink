<?php

namespace app\admin\controller;


use app\BaseController;
use app\common\model\SysMenuModel;

class SysMenu extends BaseController
{
    //列表
    public function lst()
    {
        $SysMenuModel = new SysMenuModel();
        $data['search_text'] = input("search_text");
        $list = $SysMenuModel->lst($data);
        return json($list);
    }

    //添加修改
    public function addEdit($op = 'add')
    {
        $SysMenuModel = new SysMenuModel();
        $id = input("id");
        if ($this->request->isPost()) {
            //接收参数
            $data['title'] = input("title");  //
            $data['p_id'] = input("p_id");  //父级id
            $data['type'] = input("type");  //1一级菜单 2二级菜单  3操作按钮
            $data['level'] = input("level");  //1一级菜单 2二级菜单  3操作按钮
            $data['path'] = input("path");  //路径   用于后台权限控制
            $data['route_english'] = input("route_english");  //
            $data['icon'] = input("icon");  //
            $data['component'] = input("component");  //
            $data['url'] = input("url");  //
            $data['seq'] = input("seq");  //
            $data['status'] = input("status");  //
//            $data['expand'] = input("expand");  //
            $data['buttonType'] = input("buttonType");  //

            //route_english不能为null
            if(empty($data['route_english'])){
                $data['route_english']='';
            }
            //拆分后台请求路径
            if($data['path']){
                $str=substr($data['path'],0,1);
                if($str=='/'){
                    return json(return_msg("请求后台路径不能以/开头"));
                }
                $path=explode("/",$data['path']);
                if(isset($path[0])){
                    $data['module']=$path[0];
                }else{
                    $data['module']='';
                }
                if(isset($path[1])){
                    $data['controller']=$path[1];
                }else{
                    $data['controller']='';
                }
                if(isset($path[2])){
                    $data['action']=$path[2];
                }else{
                    $data['action']='';
                }
            }else{
                $data['module']='';
                $data['controller']='';
                $data['action']='';
            }
            $res = $SysMenuModel->addEdit($data, $op, $id);
            return json($res);
        }
        $info = $SysMenuModel->getSysMenu($id);
        return json($info);
    }

    //删除
    public function del()
    {
        if ($this->request->isPost()) {
            $SysMenuModel = new SysMenuModel();
            $id = input("id");
            $res = $SysMenuModel->del($id);
            return json($res);
        }
    }

}
<?php
namespace app\admin\controller;


use app\BaseController;
use think\App;
use think\facade\Db;
use think\exception\HttpResponseException;
use think\facade\Cache;
use think\facade\Request;

class Base extends BaseController
{
    //基础控制器
    public function __construct(App $app = null)
    {
        parent::__construct($app);

        //判断是否已登录
        $sys_user = get_session_user(); //获取登录信息

        $token = get_header_token();  //获取token信息

        $sysUser = Cache::get("sys_user".$token);

        if (empty($sys_user)) {
            http_response_code(401);
            exit("登录过期，请重新登录");
        } else {
            $token = get_header_token();  //获取token信息
            if($token) {
                //1个小时更新缓存  防止redis过期
                $dif_time = (strtotime(date("Y-m-d h:i:s")) - strtotime($sys_user['redis_time'])) / 60;  //分钟
                if ($dif_time > 60) {
                    Cache::set('sys_user' . $token, $sys_user);
                }
            }
        }
        if($sys_user['role_id'] !='1'){ //超级管理员无需验证
            $res=$this->permissionDeny();
            if(!$res){
                $return_msg=json_encode(return_msg("抱歉，您没有访问权限"));
                exit("$return_msg");
            }
        }
    }
    //判断是否有该权限
    public function permissionDeny(){
        //无需验证的权限
        $data = [
            'admin-News-searchNewsType',  #搜索新闻分类
        ];

        $module = app('http')->getName();//模块名
        $controller = Request::controller();//控制器名
        $action = Request::action();//方法名

        $operat = "$module" . '-' . "$controller" . '-' . "$action";

        if (in_array($operat, $data)) {
            return 1; //有权限范围
        } else {
            $res=Db::table("sys_menu")->field("id")->where("module='$module' and controller='$controller' and action='$action' and is_del='0'")->find();

            $sys_user = get_session_user(); //获取登录信息

            $role=Db::table("sys_role")->field("role_ids")->where("id='$sys_user[role_id]'")->find();

            $arr=explode(",",$role['role_ids']);
            $id=$res['id'];
            if(in_array($id,$arr)){
                return 1; //有权限
            }else{
                return 0; //无权限
            }
        }
    }

}
<?php
namespace app\common\model;


use think\facade\Db;
use think\Model;

class SysMenuModel extends Model
{
    //菜单 模块/控制器/方法表
    protected $table = 'sys_menu';

    //获取菜单
    public function getSysMenu($id)
    {
        $data = $this->where("is_del='0' and id='$id'")->find();
        return $data;
    }

    //列表
    public function lst($data)
    {
        $where = "is_del = '0'";
        //搜索条件
        if ($data['search_text'] != '' && strlen($data['search_text']) > 0) {
            $where .= " and title like '%" . $data['search_text'] . "%'";   //关键字
        }
        $order = "seq asc";
        $field = "*";
        $list = Db::table("sys_menu")->field("$field")->where("$where")->order($order)->select();
        if ($data['search_text'] != '' && strlen($data['search_text']) > 0) {
            //搜索 无需拼接成树形
        }else{
            $list=listToTree($list,'id','p_id');  //拼装成树形
        }
        return $list;
    }

    //添加修改
    public function addEdit($data, $op, $id)
    {
        Db::startTrans();
        try {
            if ($op == 'add') {
                $res = $this->insert($data);
            } else {
                $res = $this->where("id='$id'")->update($data);
            }
        } catch (\Exception $e) {
            Db::rollback();
            return return_msg($e->getMessage()); //事务回滚   返回失败信息
        }
        Db::commit();
        return return_msg("success", true);  //返回成功信息
    }

    //删除
    public function del($id)
    {
        Db::startTrans();
        try {
            $arr["is_del"] = '1';
            $this->where("id in ($id)")->update($arr);
        } catch (\Exception $e) {
            Db::rollback();
            return return_msg($e->getMessage()); //事务回滚   返回失败信息
        }
        Db::commit();
        return return_msg("success", true);  //返回成功信息
    }

    //获取登录用户的菜单信息
    public function getMenu(){
        $user=get_session_user();
        $role=Db::table("sys_role")->field("role_ids")->where("id='$user[role_id]'")->find();
        if(empty($role['role_ids'])){
            $role['role_ids']=0;
        }

        if($user['role_id'] == 1){ //开发人员
            $where='';
        }else{
            $where="id in ( $role[role_ids] )";
        }
        $data=Db::table("sys_menu")->where("is_del='0' and type in (1,2)")->where("$where")->select();
        $i=0;
        $new_arr=[];
        foreach ($data as $k=>$v){
            $v['title']=(string)$v['title'];
            $new_arr[$i]=$v;
            $i++;
        }

        $arr['menu'] = getVueMenuTree($new_arr);     //生成vue前端所需的菜单树
        return $arr;
    }

}
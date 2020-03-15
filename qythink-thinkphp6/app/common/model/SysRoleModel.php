<?php
namespace app\common\model;


use think\facade\Db;
use think\Model;

class SysRoleModel extends Model
{
    //权限组
    protected $table = 'sys_role';

    //获取权限组
    public function getSysRole($id){
        $data=$this->where("is_del='0' and id='$id'")->find();
        return $data;
    }
    //获取所有的权限
    public function getAllRole(){
        $data=$this->field("id as value,name as label")->where("is_del='0'")->order("id asc")->select();
        return $data;
    }

    //列表
    public function lst($data)
    {
        $where = "is_del = '0'";
        $order = "id desc";
        $field = "*";
        $list = $this->field("$field")->where("$where")->order($order)->paginate(get_page_size());
        $data_list = list_page($list);//分页格式
        return $data_list;
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
            $this->where("id='$id'")->update($arr);
        } catch (\Exception $e) {
            Db::rollback();
            return return_msg($e->getMessage()); //事务回滚   返回失败信息
        }
        Db::commit();
        return return_msg("success", true);  //返回成功信息
    }

    //添加修改
    public function editMenu($data, $id)
    {
        Db::startTrans();
        try {
            $res = $this->where("id='$id'")->update($data);
        } catch (\Exception $e) {
            Db::rollback();
            return return_msg($e->getMessage()); //事务回滚   返回失败信息
        }
        Db::commit();
        return return_msg("success", true);  //返回成功信息
    }

    //获取编辑菜单并拼接成vue所需格式
    public function getEditMenu($id)
    {
        $SysMenuModel = new SysMenuModel();
        $role_ids = $this->field("role_ids")->where("id='$id'")->find();  //当前角色所拥有的权限
        $menu = $SysMenuModel->field("id,title,type,p_id")->where("is_del='0' and status='0'")->select()->toArray();
        if (empty($role_ids)) {
            $role_ids = '';
        }
        $role_ids = explode(",", $role_ids['role_ids']);  //字符串转数组
        $new_arr = [];
        $i = 0;
        foreach ($menu as $k => $v) {
            if (in_array($v['id'], $role_ids)) {
                $v['extend'] = true;
                $v['checked'] = true;
            } else {
                $v['extend'] = false;
                $v['checked'] = false;
            }
            $new_arr[$i] = $v;
            $i++;
        }
        $new_arr = listToTree($new_arr,'id','p_id');  //组装树形

        return return_msg('success',true,$new_arr);
    }

}
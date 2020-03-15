<?php
namespace app\common\model;


use think\facade\Db;
use think\Model;

class SysDictModel extends Model
{
    //数据字典
    protected $table = 'sys_dict';

    //获取数字字典
    public function getSysDict($id)
    {
        $data = $this->where("is_del='0' and id='$id'")->find();
        return $data;
    }

    //列表字典
    public function lstDict($data)
    {
       $where = "is_del = '0'";
        //搜索条件
        if ($data['search_text'] != '' && strlen($data['search_text']) > 0) {
            $where .= " and title like '%" . $data['search_text'] . "%'";   //关键字
        }
        $order = "seq desc";
        $field = "*";
        $list = $this->field("$field")->where("$where")->order($order)->select();
        return $list;
    }

    //添加修改字典
    public function addEditDict($data, $op, $id)
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

    //删除字典
    public function delDict($id)
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
}
<?php

namespace app\common\model;


use think\facade\Db;
use think\Model;

class SysLogModel extends Model
{
    //系统操作日志
    protected $table = 'sys_log';

    //操作日志
    public function addLog($data)
    {
        Db::startTrans();
        try {
            $res = $this->insert($data);
        } catch (\Exception $e) {
            Db::rollback();
            return return_msg($e->getMessage()); //事务回滚   返回失败信息
        }
        Db::commit();
        return return_msg("success", true);  //返回成功信息
    }

    //列表
    public function lst($data)
    {
        $where = "";
        //搜索条件
        if ($data['add_user_id'] != '') {
            $where .= "add_user_id ='" . $data['add_user_id'] . "'";   // 用户id
        }
        $order = "id desc";

        $field = "*";
        $list = $this->field("$field")->where("$where")->order($order)->paginate(get_page_size());
        $data_list = list_page($list);//分页格式

        return $data_list;
    }

    //删除
    public function del($id)
    {
        Db::startTrans();
        try {
            $this->delete($id);
        } catch (\Exception $e) {
            Db::rollback();
            return return_msg($e->getMessage()); //事务回滚   返回失败信息
        }
        Db::commit();
        return return_msg("success", true);  //返回成功信息
    }


}
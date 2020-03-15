<?php
namespace app\common\model;


use think\facade\Db;
use think\Model;

class SysDictDetailModel extends Model
{
    //数字字典详情
    protected $table = 'sys_dict_detail';

    //获取数字字典详情
    public function getSysDictDetail($id){
        $data=$this->where("is_del='0' and id='$id'")->find();
        return $data;
    }

    //列表数据
    public function lstDictDetail($data)
    {
        $where = "is_del = '0' and dict_code='$data[dict_code]'";

        //搜索条件
        if ($data['search_text'] != '' && strlen($data['search_text']) > 0) {
            $where .= " and name like '%" . $data['search_text'] . "%'";   //关键字
        }
        if ($data['status'] != '' && strlen($data['status']) > 0) {
            $where .= " and status ='" . $data['status'] . "'";   //全部企业
        }

        $order = "seq desc";
        $field = "*";
        $list = $this->field("$field")->where("$where")->order($order)->paginate(get_page_size());
        return $list;
    }

    //添加修改数据
    public function addEditDictDetail($data, $op, $id)
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

    //删除数据
    public function delDictDetail($id)
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


}
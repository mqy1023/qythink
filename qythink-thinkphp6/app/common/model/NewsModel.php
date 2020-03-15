<?php

namespace app\common\model;


use think\facade\Db;
use think\Model;

class NewsModel extends Model
{
    //新闻表
    protected $table = 'news';

    //列表
    public function lst($data)
    {
        $where = "is_del = '0' ";
        //搜索条件
        if ($data['search_text'] != '' && strlen($data['search_text']) > 0) {
            $where .= " and title like '%" . $data['search_text'] . "%'";   //关键字
        }
        if ($data['type'] != '' && strlen($data['type']) > 0) {
            $where .= " and type ='" . $data['type'] . "'";   //分类
        }

        if(!empty($data['startDate']) && !empty($data['endDate'])){
            $begin_date = $data['startDate'] . " 00:00:00";
            $end_date = $data['endDate'] . " 23:59:59";
            $where .= " and add_time >='$begin_date' and add_time <='$end_date'";
        }
        if(!empty($data['startDate']) && empty($data['endDate'])){
            $begin_date = $data['startDate'] . " 00:00:00";
            $where .= " and add_time >='$begin_date'";
        }
        if(empty($data['startDate']) && !empty($data['endDate'])){
            $end_date = $data['endDate'] . " 23:59:59";
            $where .= " and add_time <='$end_date'";
        }

        $order = "id desc";

        if (!empty($data['sort'])) {
            switch ($data['sort']) {
                case 'add_time':
                    $order = "add_time " . $data['order'];
                    break;
                default:
                    $a = 10;
            }
        }

        $field = "*";
        $list = $this->field("$field")->where("$where")->order($order)->paginate(get_page_size());
        $data_list = list_page($list);//分页格式
        $NewsTypeModel = new NewsTypeModel();
        foreach ($data_list['data'] as $k => $v) {
            $type=$NewsTypeModel->field("title")->where("id='$v[type]'")->find();
            $data_list['data'][$k]['type_name']=$type['title'];
        }

        return $data_list;
    }

    //添加修改
    public function addEdit($data, $op, $id)
    {
        Db::startTrans();
        try {
            if ($op == 'add') {
                $data['add_time'] = date("Y-m-d H:i:s");
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


}
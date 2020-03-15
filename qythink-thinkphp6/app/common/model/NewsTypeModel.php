<?php
namespace app\common\model;


use think\facade\Db;
use think\Model;

class NewsTypeModel extends Model
{
    //新闻分类表
    protected $table = 'news_type';
    //获取新闻分类
    public function getNewsType($id)
    {
        $data = $this->where("id='$id'")->find();
        return return_msg("success",true,$data);
    }

    //列表
    public function lst($data)
    {
        //搜索条件
        $where = "is_del ='0' ";
        if ($data['search_text'] != '' && strlen($data['search_text']) > 0) {
            $where .= " and title like '%" . $data['search_text'] . "%'";   //关键字
        }
        $order = "seq asc";
        $field = "*";
        $list = Db::table("news_type")->field("$field")->where("$where")->order($order)->select()->toArray();
        $data_list = listToTree($list,'id','p_id');//分页格式

        return return_msg("success",true,$data_list);
    }

    public function searchNewsType($data)
    {
        //搜索条件
        $where = "is_del ='0' ";
        if ($data['search_text'] != '' && strlen($data['search_text']) > 0) {
            $where .= " and title like '%" . $data['search_text'] . "%'";   //关键字
        }
        $order = "seq asc";
        $field = "*";
        $list = Db::table("news_type")->field("$field")->where("$where")->order($order)->select()->toArray();

        if($data['search_text'] != '' && strlen($data['search_text']) > 0){
            $data_list = $list;   //如果有搜索则无需生成树形
        }else{
            $data_list = listToTree($list,'id','p_id');//分页格式
        }

        return return_msg("success",true,$data_list);
    }

    public function linkNewsType($data)
    {
        //搜索条件
        $where = "is_del ='0' ";
        if ($data['search_text'] != '' && strlen($data['search_text']) > 0) {
            $where .= " and title like '%" . $data['search_text'] . "%'";   //关键字
        }
        $order = "seq asc";
        $field = "id,p_id,id as value,title as label";
        $list = Db::table("news_type")->field("$field")->where("$where")->order($order)->select();

        $data_list = listToTree($list,'id','p_id');//分页格式
        return return_msg("success",true,$data_list);
    }

    //添加修改
    public function addEdit($data, $op, $id)
    {
        Db::startTrans();
        try {
            if ($op == 'add') {
                $res = $this->insert($data);
//                $a=$this->getLastSql();
//                return $a;
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
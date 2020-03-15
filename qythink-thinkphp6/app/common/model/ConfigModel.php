<?php
namespace app\common\model;


use think\facade\Db;
use think\Model;

class ConfigModel extends Model
{
    //配置表
    protected $table = 'config';

    //添加修改配置
    public function editConfig($data)
    {
        Db::startTrans();
        try {
            foreach ($data as $k => $v) {
                $where = "name='$k'";
                $arr['value'] = "$v";
                $res = $this->where("$where")->update($arr);
            }
        } catch (\Exception $e) {
            Db::rollback();
            return return_msg($e->getMessage()); //事务回滚   返回失败信息
        }
        Db::commit();
        return return_msg("success", true);  //返回成功信息
    }

    public function getSysConfig(){
        $arr1 = $this->where("type='1'")->field("name,value")->select();
        $new_arr1=[];
        foreach ($arr1 as $k=>$v){
            $new_arr1["$v[name]"]=$v['value'];
        }
        $data['one']=$new_arr1;

        $arr2 = $this->where("type='2'")->field("name,value")->select();
        $new_arr2=[];
        foreach ($arr2 as $k=>$v){
            $new_arr2["$v[name]"]=$v['value'];
        }
        $data['two']=$new_arr2;


        $arr3 = $this->where("type='3'")->field("name,value")->select();
        $new_arr3=[];
        foreach ($arr3 as $k=>$v){
            $new_arr3["$v[name]"]=$v['value'];
        }
        $data['three']=$new_arr3;
        return $data;
    }


}
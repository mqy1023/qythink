<?php
namespace app\common\model;

use think\facade\Cache;
use think\facade\Db;
use think\Model;

class SysUserModel extends Model
{
    //用户表
    protected $table = 'sys_user';

    //获取用户信息
    public function getSysUser($id)
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
            $where .= " and name like '%" . $data['search_text'] . "%'";   //关键字
        }
        $order = "id desc";
        $field = "*";
        $list = $this->field("$field")->where("$where")->order($order)->paginate(get_page_size());
        $data_list = list_page($list);//分页格式

        //分页处理数据
        $SysRoleModel=new SysRoleModel();
        foreach ($data_list['data'] as $k => $v) {
            $role=$SysRoleModel->field("name")->where("id='$v[role_id]'")->find();
            $data_list['data'][$k]['role_name'] =$role['name'];      //
        }

        return $data_list;
    }

    //添加修改
    public function addEdit($data, $op, $id)
    {
        $res=$this->field("id")->where("id != '$id' and account='$data[account]'")->find();
        if($res['id']){
            return return_msg("账号已存在");
        }
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

    //登录
    public function login($data)
    {
        $field = "*";
        $where = "is_del='0' and account='$data[account]' and password='$data[password]'";
        //查询账号密码是否匹配
        $user = $this->field("$field")->where($where)->find();
        if (empty($user)) {
            return return_msg("请输入正确的账号或密码!"); //返回失败信息
        }
        //判断该账号是否正常使用
        if ($user['status'] == '1') {
            return return_msg("该账号已停用，请联系管理员!"); //返回失败信息
        }
        //记录登录的信息
//        $login_where="account='$data[account]' and is_del='0' ";
//        $login_data['last_login_time']=date("Y-m-d H:i:s");
//        $login_data['last_login_ip']=Request::ip();
//        $this->alias("sp")->where("$login_where")->update($login_data);

        //将用户信息session
        unset($user['password']);    //去掉密码

        $user['redis_time']=date("Y-m-d H:i:s");  //用于操作时更新缓存

        $user['token'] = create_guid();

        Cache::set("sys_user".$user['token'], $user);

        //如果安装了redis则使用， 并且修改common中的get_session_user()获取方式
//        \think\facade\Cache::store('redis')->set("sys_user".$user['token'], $user);
        //登录成功
        $token['token']=$user['token'];
        return return_msg("success", true, $token);  //返回成功信息
    }

    public function getUserInfo(){
        $user=get_session_user();
        $data=$this->where("id=$user[id]")->find();
        return $data;
    }

}
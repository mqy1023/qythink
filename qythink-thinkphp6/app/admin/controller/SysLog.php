<?php
namespace app\admin\controller;

use app\common\model\SysLogModel;

class SysLog extends Base
{

    //列表
    public function lst($op = 'page')
    {
        $SysLogModel = new SysLogModel();
        if ($op == 'page') {
            //接收变量
            $data['add_user_id'] = input("add_user_id");
            $list = $SysLogModel->lst($data);
            return json($list);
        } else {
            //搜索参数
            $param = [];
            return json($param);
        }
    }

    //删除
    public function del()
    {
        $SysLogModel = new SysLogModel();
        if ($this->request->isPost()) {

            $id = input("id");
            $res = $SysLogModel->del($id);
            return json($res);
        }
    }

}
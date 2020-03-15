<?php
namespace app\common\listener;


use app\common\model\SysLogModel;
use think\facade\Db;
use think\facade\Log;
use think\facade\Request;

class Record
{
    // 记录不需要保存的控制器和方法
    private function getConf()
    {
        //结构：模块/控制器/方法名
        return ['index/index/index',];
    }

    //操作日志记录，在tags.php中的app_end配置'app\\common\\behavior\\Record'即可每次程序结束调用该方法
    // event 事件
    public function handle($event)
    {
//        echo $event->getContent();
//        echo $event;
        if (Request::isPost()) {
            $forbidMethod = $this->getConf();
            $module = strtolower(app('http')->getName()); // 模块名
            $contro = strtolower(Request::controller());
            $act = strtolower(Request::action());
            //过滤不需要的记录的日志
            if (!in_array($module . '/' . $contro . '/' . $act, $forbidMethod)) {
                //获取操作是否成功
                $result = json_decode($event->getContent(), true);//对象转数组获取$result返回的值
                if (isset($result['status'])) {
                    $status = $result['status'];
                } else {
                    $status = false;
                }
                $data = input('');  //接收请求信息
                if (isset($data['id'])) {
                    $id = $data['id'];
                } else {
                    $id = "";
                }
                $sysUser = get_session_user();
                $role_decs = Db::table("sys_menu")->field("title")->where("module='$module' and controller='$contro' and action='$act' and is_del='0'")->find();
                $decs = $role_decs['title'];
                //操作成功，并做记录
                if (isset($sysUser['id'])) {
                    $psn_id = $sysUser['id'];
                } else {
                    $psn_id = '';
                }
                if ($status) {
                    $log = ['add_user_id' => $psn_id, 'key_code' => $id, 'ip' => request()->ip(), 'address' => get_ip_address(), 'descc' => $decs, 'content' => json_encode($data, JSON_UNESCAPED_UNICODE), 'module' => $module, 'controller' => $contro, 'action' => $act];
                    $SysLogModel = new SysLogModel();
                    //添加日志
                    $res = $SysLogModel->addLog($log);
                } else {
                    Log::record($data);
                }

            }
        }
    }

}
<?php
// 应用公共文件

/**
 * php 将数组转为树形结构
 * @param $list 传递的数组
 * @param string $id 数组中的id与$pid相关联的
 * @param string $pid 父类id
 * @param string $child child名字，自定义，
 * @param int $root 一级菜单的$pid值
 * @param bool $sort 一级菜单的键是否按0开始顺序排序
 * @return array 返回是数组格式
 */
function listToTree($list, $id = 'id', $pid = 'pid', $child = 'children', $root = 0, $sort = true)
{
    $tree = array();
    if (is_array($list)) {
        $refer = array();
        foreach ($list as $key => $data) {
            //将$pk = 'id'作为键，重新组成一个数组
            $refer[$data[$id]] = &$list[$key];
        }

        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            //一级菜单
            if ($root == $parentId) {
                $tree[$data[$id]] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][$data[$id]] = &$list[$key];

                    $parent[$child] = array_values($parent[$child]);
                }
            }
        }
    }
    //将一级菜单键从0开始按顺序排，用于json_encode(),将{}转[]
    if ($sort) {
        $tree = array_values($tree);
    }
    // 转json格式，注意中文乱码问题  $json_string = json_encode($arr,JSON_UNESCAPED_UNICODE);
    return $tree;
}

/**
 * 分页方法
 * @param $list 分页方法传递的参数，如$list=$this->paginate();
 * 返回vue前后端分离分页格式
 */
function list_page($list)
{
    $data['status'] = true;
    $data['msg'] = '';
    $data['data'] = $list->items();
    $data['pageSize'] = get_page_size();
    $data['total'] = $list->total();
    $data['page'] = (int)$list->currentPage();
    return $data;
}

/**
 *获取分页参数的大小
 * get_page_num
 */
function get_page_size()
{
    //分页大小，(一页有多少条记录)
    $pageParam = \think\facade\Request::param("pageSize");
    if (!empty((int)$pageParam)) {
        $pageSize = $pageParam;
    } else {
        //默认分页的大小
        $pageSize = 15;
    }
    //强制转为整数
    $pageSize = (int)$pageSize;
    return $pageSize;
}

//获取分页是当前的第几页
function get_current_page()
{
    $curPage = \think\facade\Request::param("page");
    if (!empty((int)$curPage)) {
        $page = $curPage;
    } else {
        //默认当前页第一页
        $page = 1;
    }
    //强制转为整数
    $page = (int)$page;
    return $page;
}

//生成vue前端所需的菜单一二级树
function getVueMenuTree($menu)
{
    $listToTree = listToTree($menu, 'id', 'p_id');
    $data = [];
    $i = 0;
    //循环拼接成vue所需格式
    foreach ($listToTree as $k => $v) {
        //第一级菜单
        $data[$i]['p_id'] = 0;
        $data[$i]['path'] = $v['route_english'];
        $data[$i]['name'] = $v['title'];

        $flag = false; //vue 需求
        if (isset($v['children'])) {
            if (count($v['children']) == 1) {
                //vue 需求
                $flag = true;
            }
        }

        $data[$i]['meta'] = ['icon' => $v['icon'], 'title' => $v['title'], 'showAlways' => $flag];
        $data[$i]['component'] = 'Main';
        //循环下一级   第二级菜单
        $x = 0;
        $arr = [];
        if (isset($v['children'])) {
            foreach ($v['children'] as $key => $val) {
                if ($val) {
                    $arr[$x]['p_id'] = 1;
                    $arr[$x]['path'] = $val['route_english'];
                    $arr[$x]['name'] = $val['title'];
                    $arr[$x]['meta'] = ['icon' => $val['icon'], 'title' => $val['title']];
                    $arr[$x]['component'] = $val['component'];
                }
                $x++;
            }
        }
        $data[$i]['children'] = $arr;
        $i++;
    }
    return $data;
}

//快捷方式返回
function return_msg($msg = '', $status = false, $data = [], $is_arr = 1)
{
    $result = ['msg' => $msg, 'status' => $status, 'data' => $data,];
    //如果$data为空，默认data返回数组
    if ($is_arr == 1 && empty($data)) {
        $result['data'] = [];  //返回空数组
    }
    return $result;
}

// 生成唯一ID
function create_guid()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
}

//根据传过来的数组，生成字串
//整形数组  $array,',',$type=1 返回的是  1,2,3,4
//字符串数组  $array,',',$type=2 返回的是  '1','2','3','4'
function array_join($array, $str = ',', $type = 1)
{

    $resultStr = "";
    for ($i = 0; $i < count($array); $i++) {
        if ($i == 0) {
            if ($type == 1) {
                $resultStr .= $array[$i];
            } else if ($type == 2) {
                $resultStr .= "'" . $array[$i] . "'";
            }
        } else {
            if ($type == 1) {
                $resultStr .= $str . $array[$i];
            } else if ($type == 2) {
                $resultStr .= $str . "'" . $array[$i] . "'";
            }
        }
    }

    return $resultStr;
}

//获取常量的名称及属性
/**
 * @param $dict_code  const_dict_detail表的dict_code
 * @param $code //数据值
 * @param $type  默认type=1系统常量  $type=2机构常量
 * @return array  name名称  extra_code扩展属性
 */
function get_dict_name($dict_code, $code, $type = '1')
{
    $arr = ['name' => '', 'extra_code' => ''];
    if ($dict_code && $code) {
        $ConstDictDetailModel = new \app\common\model\system\ConstDictDetailModel();
        $res = $ConstDictDetailModel->get_dict_name($dict_code, $code, $type);
        $arr['name'] = $res['text'];
        $arr['extra_code'] = $res['extra_code'];
    }
    return $arr;
}

// 获取请求头的信息
function get_header_token()
{
    $info = \think\facade\Request::header();
    $header = '';
    if (isset($info['access-token'])) {     //注意大小写
        //获取token信息
        $header = $info['access-token'];
    }
//    return "bb1ef811-0d41-4c3b-ac11-79a5c8db05a8";
    return $header;
}

//获取sysUser信息
function get_session_user()
{
    $token = get_header_token();  //获取token信息

    $sysUser = \think\facade\Cache::get("sys_user" . $token);//获取登录用户信息
    //如果安装了redis则使用， 并且修改SysUserModel中的login()缓存
//    $sysUser = \think\facade\Cache::store("redis")->get("sys_user".$token);
    return $sysUser;
}

//获取缓存$type=1  file文件类型缓存  $type=2 redis缓存
function get_cache($param, $type = 1)
{
    //获取缓存
    $token = get_header_token();  //获取token信息
    if ($type == 2) {
        $data = \think\facade\Cache::store("redis")->get("$param" . $token);//redis
    } else {
        $data = \think\facade\Cache::get("$param" . $token); //file
    }
    return $data;
}

/*
 * 高德地图接口,根据传递的地址，返回省-市-区县格式
 * return  [area_value='440100',area_name='广州市' area_parent_value='440000,440100' area_parent_name='广东省-广州市']
 * https://lbs.amap.com/api/webservice/guide/api/georegeo
 * */

function get_address_format($address, $city = '')
{
    $key = "2cda8765c126e44c90b50952c56265d2";

    $data['area_name'] = '';
    $data['area_value'] = '';
    $data['area_parent_name'] = '';
    $data['area_parent_value'] = '';
    $data['status'] = false;

    try {
        $url = "https://restapi.amap.com/v3/geocode/geo?key=" . $key . "&address=" . $address . "&city=$city";
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        //此接口只获取区县以上
        if ($result['status'] == 1) {
            //获取第一条记录
            if (isset($result['geocodes']['0'])) {
                $res = $result['geocodes']['0'];
                $province = $res['province'];//省
                $city = $res['city'];//市
                $district = $res['district'];//县
                $adcode = $res['adcode'];//市或县 的行政编码

                $ConstSysAreaModel = new \app\common\model\system\ConstSysAreaModel();
                //获取parent_ids编码
                $list = $ConstSysAreaModel->getConstSysAreaInfo($adcode);
                $parent_ids = $list['parent_ids'] . ',' . $adcode;
                //数据格式处理
                $area_name = "";
                $area_parent_name = "";
                if ($district) {
                    //区县有数据则取
                    $area_name = $district;
                    $area_parent_name = $province . '-' . $city . '-' . $district;
                } elseif ($city) {
                    //区县无数据则取市
                    $area_name = $city;
                    $area_parent_name = $province . '-' . $city;
                } elseif ($province) {
                    //市无数据则取省
                    $area_name = $province;
                    $area_parent_name = $province . '-' . $city;
                }
                //去除0,100000,
                if ($parent_ids) {
                    $parent_ids = trim(str_replace('0,100000,', '', $parent_ids), ',');
                }
                $data['area_name'] = $area_name;
                $data['area_value'] = $adcode;
                $data['area_parent_name'] = $area_parent_name;
                $data['area_parent_value'] = $parent_ids;
                $data['status'] = true;
            }
        }
    } catch (\Exception $e) {
        $data['status'] = false;
    }
    return $data;

}

//获取登录地址
//返回"广东省-广州市"格式
function get_ip_address()
{
    $ip = request()->ip();
//    $ip = "183.193.38.187";
    $url = "http://api.map.baidu.com/location/ip?ip=" . $ip . "&ak=wl0DEQ4hxAiGDIgh7eIFHIqgq0Dgq9cg";
    $result = file_get_contents($url);
    $result = json_decode($result, true);
    $str = "";
    try {
        //获取地址成功
        if ($result['status'] == '0') {
            $address_detail = $result['content']['address_detail'];
            if ($address_detail['province']) {
                //获取省份
                $str .= $address_detail['province'];
            }
            if ($address_detail['city']) {
                //获取市
                $str .= "-" . $address_detail['city'];
            }
            if ($address_detail['district']) {
                //获取区
                $str .= "-" . $address_detail['district'];
            }
        }
    } catch (Exception $e) {
        return "";
    }
    return $str;
}

//$month 循环日期 $data数据格式  补全缺失的日期 用于统计部分
function formatMonth($month, $data)
{
    $new_arr = [];
    $x = 0;
    //处理数据  将缺失的年月份补全
    foreach ($month as $k => $v) {
        $flag = true;
        foreach ($data as $key => $val) {
            if ($val['title'] == "$v") {
                $flag = false;
                $new_arr[$x] = $val;
                break;
            }
        }
        if ($flag) {
            $new_arr[$x]['title'] = $v;
            $new_arr[$x]['value'] = 0;
        }
        $x++;
    }
    return $new_arr;
}

function geturl($url)
{
    $headerArray = array("Content-type:application/json;", "Accept:application/json");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output, true); //返回数组
    return $output;
}


function posturl($url, $data)
{
    $data = json_encode($data);
    $headerArray = array("Content-type:application/json;charset='utf-8'", "Accept:application/json");
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return json_decode($output, true);  //返回数组
}


/**
 * xml转数组
 * @param $xml  传递的是xml格式
 * return 返回格式是数组
 */
function xml_to_array($xml)
{
    $xml_parser = xml_parser_create();
    if (xml_parse($xml_parser, $xml, true)) {
        //验证xml格式正确
        $xml = simplexml_load_string($xml); //xml转object
        $xml = json_encode($xml);  //objecct转json
        $arr = json_decode($xml, true); //json转array
        return $arr;
    } else {
        xml_parser_free($xml_parser);
        return $res = [];
    }
}

/**
 * excel 导入数据
 * @param $file_code  附件的编码
 * @param int $i 从第几行开始获取数据  默认 第2行
 * @param $col  从第几列开始获取数据    默认 第A列  必须是大写
 * @param $col_n  获取到第几列  填写大写字母 如 H
 */
function import_excel($file_code, $row = 2, $col_n = '', $col = 'A')
{
    $SysAttachFileModel = new \app\common\model\SysAttachFileModel();
    $fileInfo = $SysAttachFileModel->getSysAttachFile("$file_code");  //获取附件信息
    include "../extend/PHPExcel/PHPExcel.php";
    if ($fileInfo['suffix'] == "xlsx") {  ////后缀名xlsx
        $reader = \PHPExcel_IOFactory::createReader('Excel2007');
    } else {
        $reader = \PHPExcel_IOFactory::createReader('Excel5');
    }
    try {
        $filePath = Config::get("kc.upload_root_path") . '/' . $fileInfo['file_path'];  //文件路径
        //载入excel文件
        $excel = $reader->load("$filePath", $encode = 'utf-8');
        //读取第一张表
        $sheet = $excel->getSheet(0);
        //获取总行数
        $row_num = $sheet->getHighestRow();
        //获取总列数
        $col_num = $sheet->getHighestColumn();  //获取的是如  H , G

    } catch (\Exception $e) {
        return return_msg($e->getMessage()); //返回失败信息
    }
    if (!empty($col_n)) {
        $col_num = $col_n;  //获取到第几列
    }

    //将字母转数字
    $str = strlen("$col_num");
    if ($str > 1) {
        $a = substr("$col_num", 0, 1);
        $b = substr("$col_num", 1, 1);
        $num = intval((ord("$a") - 65 + 1) * 26) + intval(ord("$b") - 65);  //获取多少列
    } else {
        $num = ord("$col_num") - 65;  //获取多少列
    }
    $col = intval(ord("$col")) - 65;// 从大写A开始
    //将字母转数字

    $data = []; //数组形式获取表格数据
    $x = 0;
    //先循环行，再循环列
    for ($i = $row; $i <= $row_num; $i++) { //行数是以第2行开始
        $y = 0;
        for ($j = "$col"; $j <= $num; $j++) { //$col 默认为0 也就是从A开始   $num 从A为0开始数
            $v = ($j / 26);
            if ($v >= 1) {
                $v = intval($v);
                $val = chr($v + 64) . chr($j - $v * 26 + 65);   //$val  得到的值：例 AB  AG  AK
            } else {
                $val = chr($j + 65);   //$val  得到的值：例 D  G  K
            }
            $data[$x][$y] = (string)$sheet->getCell($val . $i)->getValue();
            $y++;
        }
        $x++;
    }
    if (!empty($data)) {
        return return_msg('success', true, $data); //返回获取excel信息
    } else {
        return return_msg('获取excel内容为空');
    }
}

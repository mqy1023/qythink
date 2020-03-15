<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

require __DIR__ . '/../vendor/autoload.php';


// 支持事先使用静态方法设置Request对象和Config对象
//解决跨域
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin,Authorization,access-Token,X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET,POST');
//过滤OPTIONS请求
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    return 0;
}

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);

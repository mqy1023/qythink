QyThink -- 基于thinkphp6&iview-admin前后端分离开发框架
===============

## 核心内容
* 采用最新版的thinkphp6.0框架
* 前端采用`iview-admin2.0`构建，前后端分离
* 后台用户管理
* 配置管理
* 菜单、角色、权限管理
* 操作日志记录管理
* 上传图片文件

##### 一、安装步骤
* 1、Composer安装
~~~
composer install
~~~
* 2、执行sql文件

##### 二、Nginx支持PATHINFO模式
`url`访问时隐藏`index.php`，需在`mamp pro`中设置`nginx`的`Custom`如下：
```
location / {
	if (!-e $request_filename){
		rewrite  ^(.*)$  /index.php?s=$1  last; 
		break;
	}

}
```

##### 三、支持多应用模式
[官方文档](https://www.kancloud.cn/manual/thinkphp6_0/1297876)


##### 四、支持跨域
在`public/index.php` 加入以下：
```php
// 支持事先使用静态方法设置Request对象和Config对象
//解决跨域
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin,Authorization,access-Token,X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET,POST');
//过滤OPTIONS请求
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    return 0;
}
```


##### 五、缓存
```
默认情况下，文件缓存数据是区分不同应用的，
比如`admin`模块中设置的某个缓存，`index`模块获取的话是空的，
如果你希望缓存跨应用，可以在cache.php中设置一个统一的数据缓存path目录。

'path'       => '../runtime/file/',
```




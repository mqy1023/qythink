<?php
namespace app\index\controller;

use think\facade\Request;

class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function uploadFile(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        try {
            validate(['image'=>'filesize:10240|fileExt:jpg|image:200,200,jpg'])
                ->check([$file]);
            $savename = \think\facade\Filesystem::disk('public')->putFile( 'uploads', $file);
            $request = Request::instance();
            $data['path']=$savename;
            $data['complete_path']=$request->domain().'/storage/'.$savename;
            return json(return_msg("success",true,$data));
        } catch (think\exception\ValidateException $e) {
            // 上传失败获取错误信息
            $res=$file->getMessage();
            return json(return_msg("$res",false));
        }
    }
}

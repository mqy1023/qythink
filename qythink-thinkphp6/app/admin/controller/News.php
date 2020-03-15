<?php

namespace app\admin\controller;


use app\common\model\NewsModel;
use app\common\model\NewsTypeModel;

class News extends Base
{
    //列表
    public function lst($op = 'page')
    {
        $NewsModel = new NewsModel();
        if ($op == 'page') {
            //接收变量
            $data['search_text'] = input("search_text");
            $data['startDate'] = input("startDate");
            $data['endDate'] = input("endDate");
            $data['sort'] = input("sort");
            $data['order'] = input("order");
            $data['type'] = input("type");
            $list = $NewsModel->lst($data);
            return json($list);
        } else {
            //搜索参数
            $param = [];
            return json($param);
        }
    }

    //添加修改
    public function addEdit($op = 'add')
    {
        $NewsModel = new NewsModel();
        $id = input("id");
        if ($this->request->isPost()) {
            //接收参数
            $data['type'] = input("type");  //分类
            $data['title'] = input("title");  //
            $data['author'] = input("author");  //
            $data['label'] = input("label");  //
            $data['content'] = input("content");  //
            $res = $NewsModel->addEdit($data, $op, $id);
            return json($res);
        }
        $info = [];
        return json($info);
    }

    //删除
    public function del()
    {
        $NewsModel = new NewsModel();
        if ($this->request->isPost()) {

            $id = input("id");
            $res = $NewsModel->del($id);
            return json($res);
        }
    }


    //新闻分类
    public function lstType($op = 'page')
    {
        $NewsTypeModel = new NewsTypeModel();
        if ($op == 'page') {
            //接收变量
            $data['search_text'] = input("search_text");
            $list = $NewsTypeModel->lst($data);
            return json($list);
        } else {
            //搜索参数
            $param = [];
            return json($param);
        }
    }

    //新闻分类关键字搜索
    public function searchNewsType(){
        $NewsTypeModel = new NewsTypeModel();
        $data['search_text'] = input("search_text");
        $list = $NewsTypeModel->searchNewsType($data);
        return json($list);
    }

    //搜索栏的二级联动
    public function linkNewsType(){
        $NewsTypeModel = new NewsTypeModel();
        $data['search_text'] = input("search_text");
        $list = $NewsTypeModel->linkNewsType($data);
        return json($list);
    }

    //添加修改分类
    public function addEditType($op = 'add')
    {
        $NewsTypeModel = new NewsTypeModel();
        $id = input("id");
        if ($this->request->isPost()) {
            //接收参数
            $data['p_id'] = input("p_id");  //
            $data['title'] = input("title");//
            $data['p_title']= input("p_title");  //
            $data['level'] = input("level");  //
            $data['seq'] = input("seq");  //
            $data['status'] = input("status");  //

            if($data['p_id']=='0'){
                $data['p_title']='顶级';
            }

            $res = $NewsTypeModel->addEdit($data, $op, $id);
            return json($res);
        }
        $info = $NewsTypeModel->getNewsType($id);
        return json($info);
    }

    //删除分类
    public function delType()
    {
        if ($this->request->isPost()) {
            $NewsTypeModel = new NewsTypeModel();
            $id = input("id");
            $res = $NewsTypeModel->del($id);
            return json($res);
        }
    }

}
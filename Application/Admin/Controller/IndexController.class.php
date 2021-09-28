<?php

namespace Admin\Controller;

use Think\Controller;

class IndexController extends Controller
{

    public function index()
    {
        $this->display();
    }

    public function main()
    {
        $this->assign([
            '_page_btn_name' => '添加商品',
            '_page_title' => '欢迎页',
            '_page_btn_link' => U('Goods/add')
        ]);
        $this->display();
    }

    public function menu()
    {
        $this->display();
    }

    public function top()
    {
        $this->display();
    }
}
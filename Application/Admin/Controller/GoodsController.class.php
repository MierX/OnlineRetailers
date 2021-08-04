<?php

namespace Admin\Controller;

use Think\Controller;

class GoodsController extends Controller
{

    public function __construct()
    {

        parent::__construct();

        // 接收模型并保存到模型中
        $this->model = D('goods');
    }

    /**
     * 添加商品
     */
    public function add()
    {

        // 判断是否有表单提交
        if (IS_POST) {
            /**
             * create方法
             * 第一个参数是要操作的数据
             * 第二个参数是要操作的类型（1：添加 2：修改）
             * $_POST：未经过过滤的原始表单数据
             * I('post.')：经过过滤的post数据，可以过滤XSS攻击
             */
            if ($this->model->create(I('post.'), 1)) {
                // 插入到数据库
                if ($this->model->add()) {
                    $this->success('操作成功！', U('lst'));
                    exit;
                }
            }
            // 显示失败信息
            $this->error($this->model->getError());
        }

        // 显示表单页面
        $this->assign([
            '_page_btn_name' => '商品列表',
            '_page_title' => '添加商品页',
            '_page_btn_link' => U('lst')
        ]);
        $this->display();
    }

    /**
     * 商品列表页
     */
    public function lst()
    {

        // 返回数据和翻页
        $data = $this->model->search();

        // 显示列表页
        $this->assign($data);
        $this->assign([
            '_page_btn_name' => '添加商品',
            '_page_title' => '商品列表页',
            '_page_btn_link' => U('add')
        ]);
        $this->display();
    }

    /**
     * 修改商品
     */
    public function edit()
    {

        // 判断是否有表单提交
        if (IS_POST) {
            if ($this->model->create(I('post.'), 2)) {
                // save方法的返回值，如果失败则返回false，如果成功又分两种情况，未修改则返回0，修改了则返回受影响的记录数
                if ($this->model->save() !== false) {
                    $this->success('操作成功！', U('lst'));
                    exit;
                }
            }
            // 显示失败信息
            $this->error($this->model->getError());
        }

        // 显示表单页面
        $this->assign('data', $this->model->find(I('get.id', 0)));
        $this->assign([
            '_page_btn_name' => '商品列表',
            '_page_title' => '添加商品页',
            '_page_btn_link' => U('lst')
        ]);
        $this->display();
    }

    /**
     * 删除商品
     */
    public function del()
    {

        if ($this->model->delete(I('get.id'))) {
            $this->success('删除成功', U('lst'));
        } else {
            $this->error('删除失败！原因：' . $this->model->getError());
        }
    }
}
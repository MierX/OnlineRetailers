<?php

namespace Admin\Controller;

use Think\Controller;

class goodsController extends Controller
{

    private $model;

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
            if ($this->model->create(I('post.'), 1)) {
                // 插入到数据库
                if ($this->model->add()) {
                    $this->success('添加成功！', U('lst?p=' . I('get.p')));
                    exit;
                }
            }
            // 显示失败信息
            $this->error($this->model->getError());
        }

        $catData = [];
        D('category')->getChildren($catData, 0);

        // 显示表单页面
        $this->assign([
            '_page_btn_name' => '商品列表',
            '_page_title' => '添加商品页',
            '_page_btn_link' => U('lst'),
            'level' => D('member_level')->field('id, level_name')->select(),
            'catData' => $catData,
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

        $catData = [];
        D('category')->getChildren($catData, 0);

        // 显示列表页
        $this->assign($data);
        $this->assign([
            '_page_btn_name' => '添加商品',
            '_page_title' => '商品列表页',
            '_page_btn_link' => U('add'),
            'catData' => $catData,
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
                if ($this->model->save() !== false) {
                    $this->success('修改成功！', U('lst', ['p' => I('get.p', 1)]));
                    exit;
                }
            }
            // 显示失败信息
            $this->error($this->model->getError());
        }

        $catData = [];
        D('category')->getChildren($catData, 0);

        // 设置页面中的信息
        $this->assign('data', $this->model->find(I('get.id', 0)));
        $this->assign([
            '_page_btn_name' => '商品列表',
            '_page_title' => '编辑商品页',
            '_page_btn_link' => U('lst'),
            'brands' => M('brands')->field('id, brand_name')->select(),
            'catData' => $catData,
            'goodsCat' => M('goods_cat')->field('cat_id')->where(['goods_id' => I('get.id')])->select(),
        ]);
        $this->display();
    }

    /**
     * 删除商品
     */
    public function del()
    {
        if ($this->model->delete(I('get.id', 0)) !== false) {
            $this->success('删除成功！', U('lst', ['p' => I('get.p'), 1]));
            exit;
        } else {
            $this->error('删除失败！原因：' . $this->model->getError());
        }
    }
}
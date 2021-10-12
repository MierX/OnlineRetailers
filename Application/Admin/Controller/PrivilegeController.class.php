<?php

namespace Admin\Controller;

use Think\Controller;

class PrivilegeController extends Controller
{

    private $model;

    public function __construct()
    {
        parent::__construct();

        // 接收模型并保存到模型中
        $this->model = D('privilege');
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

        // 显示表单页面
        $this->assign('parentData', $this->model->getTree());
        $this->assign([
            '_page_btn_name' => '管理权限列表',
            '_page_title' => '添加管理权限页',
            '_page_btn_link' => U('lst'),
        ]);
        $this->display();
    }

    /**
     * 商品列表页
     */
    public function lst()
    {
        $data = $this->model->getTree();
        $this->assign([
            'data' => $data,
        ]);
        $this->assign([
            '_page_btn_name' => '添加管理权限',
            '_page_title' => '管理权限列表页',
            '_page_btn_link' => U('add'),
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

        // 设置页面中的信息
        $this->assign('data', $this->model->find(I('get.id', 0)));
        $this->assign([
            'parentData' => $this->model->getTree(),
            'children' => $this->model->getChildren($id),
        ]);
        $this->assign([
            '_page_btn_name' => '管理权限列表',
            '_page_title' => '编辑管理权限页',
            '_page_btn_link' => U('lst'),
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
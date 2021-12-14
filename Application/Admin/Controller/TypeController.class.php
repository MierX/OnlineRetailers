<?php

namespace Admin\Controller;
class TypeController extends BaseController
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = D('Type');
    }

    public function add()
    {
        if (IS_POST) {
            if ($this->model->create(I('post.'), 1)) {
                if ($this->model->add()) {
                    $this->success('添加成功！', U('lst?p=' . I('get.p')));
                    exit;
                }
            }
            $this->error($this->model->getError());
        }

        // 设置页面中的信息
        $this->assign([
            '_page_title' => '添加类型',
            '_page_btn_name' => '类型列表',
            '_page_btn_link' => U('lst'),
        ]);
        $this->display();
    }

    public function edit()
    {
        $id = I('get.id');
        if (IS_POST) {
            if ($this->model->create(I('post.'), 2)) {
                if ($this->model->save() !== FALSE) {
                    $this->success('修改成功！', U('lst', ['p' => I('get.p', 1)]));
                    exit;
                }
            }
            $this->error($this->model->getError());
        }

        // 设置页面中的信息
        $this->assign([
            '_page_title' => '修改类型',
            '_page_btn_name' => '类型列表',
            '_page_btn_link' => U('lst'),
            'data' => M('Type')->find($id),
        ]);
        $this->display();
    }

    public function delete()
    {
        if ($this->model->delete(I('get.id', 0)) !== FALSE) {
            $this->success('删除成功！', U('lst', ['p' => I('get.p', 1)]));
            exit;
        } else {
            $this->error($this->model->getError());
        }
    }

    public function lst()
    {
        $data = $this->model->search();

        // 设置页面中的信息
        $this->assign([
            '_page_title' => '类型列表',
            '_page_btn_name' => '添加类型',
            '_page_btn_link' => U('add'),
            'data' => $data['data'],
            'page' => $data['page'],
        ]);
        $this->display();
    }
}
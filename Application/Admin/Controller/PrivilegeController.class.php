<?php

namespace Admin\Controller;
class PrivilegeController extends BaseController
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = D('Privilege');
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
            '_page_title' => '添加权限',
            '_page_btn_name' => '权限列表',
            '_page_btn_link' => U('lst'),
            'parentData' => $this->model->getTree(),
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
            '_page_title' => '修改权限',
            '_page_btn_name' => '权限列表',
            '_page_btn_link' => U('lst'),
            'data' => M('Privilege')->find($id),
            'parentData' => $this->model->getTree(),
            'children' => $this->model->getChildren($id),
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
        // 设置页面中的信息
        $this->assign(array(
            '_page_title' => '权限列表',
            '_page_btn_name' => '添加权限',
            '_page_btn_link' => U('add'),
            'data' => $this->model->getTree(),
        ));
        $this->display();
    }
}
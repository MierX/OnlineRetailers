<?php

namespace Admin\Controller;
class AdminController extends BaseController
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = D('Admin');
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
            'roleData' => D('Role')->select(),
            '_page_title' => '添加管理员',
            '_page_btn_name' => '管理员列表',
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
            'roleId' => D('admin_role')->field('GROUP_CONCAT(role_id) role_id')->where(['admin_id' => ['eq', $id]])->find()['role_id'],
            'roleData' => D('Role')->select(),
            '_page_title' => '修改管理员',
            '_page_btn_name' => '管理员列表',
            '_page_btn_link' => U('lst'),
            'data' => M('Admin')->find($id),
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
            '_page_title' => '管理员列表',
            '_page_btn_name' => '添加管理员',
            '_page_btn_link' => U('add'),
            'data' => $data['data'],
            'page' => $data['page'],
        ]);
        $this->display();
    }
}
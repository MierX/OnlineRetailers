<?php

namespace Admin\Controller;
class AttributeController extends BaseController
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = D('Attribute');
    }

    public function add()
    {
        if (IS_POST) {
            if ($this->model->create(I('post.'), 1)) {
                if ($this->model->add()) {
                    $this->success('添加成功！', U('lst?p=' . I('get.p') . '&type_id=' . I('get.type_id')));
                    exit;
                }
            }
            $this->error($this->model->getError());
        }

        // 设置页面中的信息
        $this->assign([
            '_page_title' => '添加属性表',
            '_page_btn_name' => '属性表列表',
            '_page_btn_link' => U('lst?type_id=' . I('get.type_id')),
        ]);
        $this->display();
    }

    public function edit()
    {
        if (IS_POST) {
            if ($this->model->create(I('post.'), 2)) {
                if ($this->model->save() !== FALSE) {
                    $this->success('修改成功！', U('lst', ['p' => I('get.p', 1), 'type_id' => I('get.type_id')]));
                    exit;
                }
            }
            $this->error($this->model->getError());
        }

        // 设置页面中的信息
        $this->assign([
            '_page_title' => '修改属性表',
            '_page_btn_name' => '属性表列表',
            '_page_btn_link' => U('lst?type_id=' . I('get.type_id')),
            'data' => M('Attribute')->find(I('get.id')),
        ]);
        $this->display();
    }

    public function delete()
    {
        if ($this->model->delete(I('get.id', 0)) !== FALSE) {
            $this->success('删除成功！', U('lst', ['p' => I('get.p', 1), 'type_id' => I('get.type_id')]));
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
            '_page_title' => '属性表列表',
            '_page_btn_name' => '添加属性',
            '_page_btn_link' => U('add?type_id=' . I('get.type_id')),
            'data' => $data['data'],
            'page' => $data['page'],
        ]);
        $this->display();
    }
}
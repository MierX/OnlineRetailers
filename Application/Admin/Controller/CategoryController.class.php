<?php

namespace Admin\Controller;
class CategoryController extends BaseController
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = D('Category');
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
            'catData' => $this->model->getTree(),
            '_page_title' => '添加分类',
            '_page_btn_name' => '分类列表',
            '_page_btn_link' => U('lst'),
        ]);
        $this->display();
    }

    public function edit()
    {
        $id = I('get.id');
        // M:生成的是父类模型\Think\Model
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
            '_page_title' => '修改分类',
            '_page_btn_name' => '分类列表',
            '_page_btn_link' => U('lst'),
            'children' => $this->model->getChildren($id),
            'data' => $this->model->find($id),
            'catData' => $this->model->getTree(),
        ]);
        $this->display();
    }

    /**
     * 列表页
     */
    public function lst()
    {
        // 设置页面信息
        $this->assign([
            'data' => $this->model->getTree(),
            '_page_title' => '分类列表',
            '_page_btn_name' => '添加新分类',
            '_page_btn_link' => U('add'),
        ]);
        $this->display();
    }

    /**
     * 删除
     */
    public function delete()
    {
        if (FALSE !== $this->model->delete(I('get.id')))
            $this->success('删除成功！', U('lst'));
        else
            $this->error('删除失败！原因：' . $this->model->getError());
    }
}














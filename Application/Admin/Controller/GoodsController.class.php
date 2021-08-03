<?php

namespace Admin\Controller;

use Think\Controller;

class GoodsController extends Controller
{

    public function add()
    {

        // 判断是否有表单提交
        if (IS_POST) {
            // 接收模型并保存到模型中
            $model = D('goods');

            /**
             * create方法
             * 第一个参数是要操作的数据
             * 第二个参数是要操作的类型（1：添加 2：修改）
             * $_POST：未经过过滤的原始表单数据
             * I('post.')：经过过滤的post数据，可以过滤XSS攻击
             */
            if ($model->create(I('post.'), 1)) {
                // 插入到数据库
                if ($model->add()) {
                    $this->success('操作成功！', U('lst'));
                    exit;
                }
            }
            // 显示失败信息
            $this->error($model->getError());
        }

        // 显示表单页面
        $this->display();
    }

    /**
     * 商品列表页
     */
    public function lst() {

        // 显示列表页
        $this->display();
    }
}
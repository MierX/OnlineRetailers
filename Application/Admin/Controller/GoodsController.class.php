<?php

namespace Admin\Controller;
class GoodsController extends BaseController
{
    protected $model;
    protected $gnModel;
    protected $gaModel;
    protected $catModel;

    public function __construct()
    {
        parent::__construct();
        $this->model = D('goods');
        $this->gaModel = D('goods_attr');
        $this->gaModel = D('goods_attr');
        $this->gpModel = D('goods_pic');
        $this->catModel = D('category');
    }

    /**
     * 库存量页面
     */
    public function goods_number()
    {
        // 接收商品ID
        $id = I('get.id');

        // 处理表单
        if (IS_POST) {
            // 先删除原库存
            $this->gnModel->where(['goods_id' => ['eq', $id]])->delete();

            // 先计算商品属性ID和库存量的比例
            $gaid = I('post.goods_attr_id');
            $gn = I('post.goods_number');
            $rate = count($gaid) / count($gn);

            // 取第几个商品属性ID
            $_i = 0;

            // 循环库存量
            foreach ($gn as $v) {
                // 把下面取出来的ID放这里
                $_goodsAttrId = [];

                // 后来从商品属性ID数组中取出 $rate 个，循环一次取一个
                for ($i = 0; $i < $rate; $i++) {
                    $_goodsAttrId[] = $gaid[$_i];
                    $_i++;
                }

                // 先升序排列，以数字的形式排序
                sort($_goodsAttrId, SORT_NUMERIC);

                $this->gnModel->add([
                    'goods_id' => $id,
                    'goods_attr_id' => (string)implode(',', $_goodsAttrId),
                    'goods_number' => $v,
                ]);
            }
            $this->success('设置成功！', U('goods_number?id=' . I('get.id')));
            exit;
        }

        // 根据商品ID取出这件商品所有可选属性的值
        $gaData = $this->gaModel
            ->alias('a')
            ->field('a.*,b.attr_name')
            ->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id=b.id')
            ->where([
                'a.goods_id' => ['eq', $id],
                'b.attr_type' => ['eq', '可选'],
            ])
            ->select();

        // 处理这个二维数组：转化成三维：把属性相同的放到一起
        $_gaData = [];
        foreach ($gaData as $v) {
            $_gaData[$v['attr_name']][] = $v;
        }

        $this->assign([
            '_page_title' => '库存量',
            '_page_btn_name' => '返回列表',
            '_page_btn_link' => U('lst'),
            'gaData' => $_gaData,
            'gnData' => $this->gnModel->where(['goods_id' => $id])->select(),
        ]);
        $this->display();
    }

    /**
     * 处理删除属性
     */
    public function ajaxDelAttr()
    {
        $goodsId = addslashes(I('get.goods_id'));
        $gaid = addslashes(I('get.gaid'));
        $this->gaModel->delete($gaid);

        // 删除相关库存量数据
        $this->gnModel->where(['goods_id' => ['EXP', "=$goodsId or AND FIND_IN_SET($gaid, attr_list)"]])->delete();
    }

    /**
     * 处理获取属性的AJAX请求
     */
    public function ajaxGetAttr()
    {
        echo json_encode(D('Attribute')->where(['type_id' => ['eq', I('get.type_id')]])->select());
    }

    /**
     * 处理AJAX删除图片的请求
     */
    public function ajaxDelPic()
    {
        $picId = I('get.picid');

        // 从硬盘删除图片
        deleteImage($this->gpModel->field('pic,sm_pic,mid_pic,big_pic')->find($picId));

        // 从数据库中删除记录
        $this->gpModel->delete($picId);
    }

    /**
     * 显示和处理表单
     */
    public function add()
    {
        // 判断用户是否提交了表单
        if (IS_POST) {
            if ($this->model->create(I('post.'), 1)) {
                // 插入到数据库中
                if ($this->model->add()) {
                    // 显示成功信息并等待1秒之后跳转
                    $this->success('操作成功！', U('lst'));
                    exit;
                }
            }

            $error = $this->model->getError();
            $this->error($error);
        }

        $this->assign([
            'catData' => D('category')->getTree(),
            'mlData' => D('member_level')->select(),
            '_page_title' => '添加新商品',
            '_page_btn_name' => '商品列表',
            '_page_btn_link' => U('lst'),
        ]);
        $this->display();
    }

    // 显示和处理表单
    public function edit()
    {
        $id = I('get.id');
        if (IS_POST) {
            if ($this->model->create(I('post.'), 2)) {
                if (FALSE !== $this->model->save()) {
                    $this->success('操作成功！', U('lst'));
                    exit;
                }
            }
            $error = $this->model->getError();
            $this->error($error);
        }

        // 根据ID取出要修改的商品的原信息
        $data = $this->model->find($id);

        // 取出这件商品已经设置好的会员价格
        $mpData = D('member_price')->where(['goods_id' => ['eq', $id]])->select();
        $_mpData = [];
        foreach ($mpData as $v) {
            $_mpData[$v['level_id']] = $v['price'];
        }

        // 取出当前类型下所有的属性
        $attrData = D('Attribute')
            ->alias('a')
            ->field('a.id attr_id,a.attr_name,a.attr_type,a.attr_option_values,b.attr_value,b.id')
            ->join('LEFT JOIN __GOODS_ATTR__ b ON (a.id=b.attr_id AND b.goods_id=' . $id . ')')
            ->where(['a.type_id' => ['eq', $data['type_id']]])
            ->select();

        // 设置页面信息
        $this->assign([
            'catData' => $this->catModel->getTree(),
            'mlData' => D('member_level')->select(),
            'mpData' => $_mpData,
            'gpData' => $this->gpModel->field('id,mid_pic')->where(['goods_id' => ['eq', $id],])->select(),
            'gcData' => D('goods_cat')->field('cat_id')->where(['goods_id' => ['eq', $id]])->select(),
            'gaData' => $attrData,
            '_page_title' => '修改商品',
            '_page_btn_name' => '商品列表',
            '_page_btn_link' => U('lst'),
            'data' => $data,
        ]);
        $this->display();
    }

    // 商品列表页
    public function lst()
    {
        // 返回数据和翻页
        $data = $this->model->search();

        // 设置页面信息
        $this->assign([
            'catData' => $this->catModel->getTree(),
            '_page_title' => '商品列表',
            '_page_btn_name' => '添加新商品',
            '_page_btn_link' => U('add'),
            'data' => $data['data'],
            'page' => $data['page'],
        ]);

        $this->display();
    }

    public function delete()
    {
        $this->model = D('goods');
        if (FALSE !== $this->model->delete(I('get.id')))
            $this->success('删除成功！', U('lst'));
        else
            $this->error('删除失败！原因：' . $this->model->getError());
    }
}














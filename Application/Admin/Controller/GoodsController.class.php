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

        $data = $this->model->find(I('get.id', 0));

        $catData = [];
        D('category')->getChildren($catData, 0);

        $goodsAttrs = D('attribute')
            ->alias('a')
            ->field('a.*, a.id as attr_id, b.attr_value, b.id as goods_attr_id')
            ->join('goods_attribute b on (a.id = b.attr_id and b.goods_id = ' . $data['id'] . ')', 'LEFT')
            ->where(['a.type_id' => $data['type_id']])
            ->select();
        $goodsAttrsText = "";
        foreach ($goodsAttrs as $v) {
            if (!in_array($v['attr_id'], $hasAttrId)) {
                $hasAttrId[] = $v['attr_id'];
                $aText = '[+]';
            } else {
                $aText = '[-]';
            }

            $goodsAttrsText .= '<li>';
            $goodsAttrsText .= '<input type="hidden" name="goods_attr_id[]" value="' . $v['goods_attr_id'] . '" />';
            if ($v['attr_type'] == '可选') {
                $goodsAttrsText .= '<a onclick="addNewAttr(this)">' . $aText . '</a>';
            }
            $goodsAttrsText .= $v['attr_name'] . '：';
            if ($v['attr_type'] == '唯一') {
                $goodsAttrsText .= '<input type="text" name="attr_value[' . $v['attr_id'] . ']" value="' . $v['attr_value'] . '" />';
            } else {
                $goodsAttrsText .= '<select name="attr_value[' . $v['attr_id'] . '][]"><option value="">请选择</option>';
                foreach (explode('，', $v['attr_values']) as $_v) {
                    if ($v['attr_value'] == $_v) {
                        $goodsAttrsText .= '<option value="' . $_v . '" selected="selected">' . $_v . '</option>';
                    } else {
                        $goodsAttrsText .= '<option value="' . $_v . '">' . $_v . '</option>';
                    }
                }
                $goodsAttrsText .= '</select>';
            }
            $goodsAttrsText .= '</li>';
        }

        // 设置页面中的信息
        $this->assign('data', $data);
        $this->assign([
            '_page_btn_name' => '商品列表',
            '_page_title' => '编辑商品页',
            '_page_btn_link' => U('lst'),
            'brands' => M('brands')->field('id, brand_name')->select(),
            'catData' => $catData,
            'goodsCat' => M('goods_cat')->field('cat_id')->where(['goods_id' => I('get.id')])->select(),
            'level' => D('member_level')->field('id, level_name')->select(),
            'member_price' => array_column(D('member_price')->where(['goods_id' => I('get.id')])->field('level_id, price')->select() ?: [], 'price', 'level_id'),
            'attrText' => $goodsAttrsText,
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

    /**
     * 获取属性
     */
    public function getAttribute()
    {
        $typeId = I('get.type_id');
        $attrData = D('attribute')->field('id, attr_name, attr_type, attr_values')->where(['type_id' => $typeId])->select();
        echo json_encode($attrData, 256);
    }

    /**
     * 删除属性
     */
    public function delAttribute()
    {
        $rs = D('goods_attribute')->delete(I('get.id', 0));

        // 删除相关库存量
        if ($rs) {
            D('goods_inventory')->where([
                'goods_id' => ['EXP', '=' . addslashes(I('get.goods_id', 0)) . 'or AND FIND_IN_SET(' . addslashes(I('get.id', 0)) . ', attr_id)'],
            ])->delete();
        }
        echo json_encode(['code' => $rs], 256);
    }
}
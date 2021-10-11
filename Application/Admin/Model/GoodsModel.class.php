<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class goodsModel extends Model
{

    protected $insertFields = 'goods_name,market_price,shop_price,is_on_sale,is_delete,goods_desc,brand_id,cat_id,type_id';

    protected $updateFields = 'id,goods_name,market_price,shop_price,is_on_sale,is_delete,goods_desc,brand_id,cat_id,type_id';

    protected $_validate = [
        ['goods_name', 'require', '商品名称不能为空！', 1, 'regex', 3],
        ['goods_name', '1,150', '商品名称的值最长不能超过 150 个字符！', 1, 'length', 3],
        ['market_price', 'require', '市场价格不能为空！', 1, 'regex', 3],
        ['market_price', 'currency', '市场价格必须是货币格式！', 1, 'regex', 3],
        ['shop_price', 'require', '本店价格不能为空！', 1, 'regex', 3],
        ['shop_price', 'currency', '本店价格必须是货币格式！', 1, 'regex', 3],
        ['is_on_sale', '是,否', "是否上架的值只能是在 '是,否' 中的一个值！", 2, 'in', 3],
        ['is_delete', '是,否', "是否放到回收站的值只能是在 '是,否' 中的一个值！", 2, 'in', 3],
        ['cat_id', 'require', '必须选择主分类！', 1, 'regex', 3],
    ];

    /**
     * 这个方法会在添加之前被调用（钩子函数）
     * @param $data array 表单中即将要插入到数据库中的数据（数组形式）
     * @param $options
     * @return false
     */
    protected function _before_insert(&$data, $options)
    {
        // 过滤商品描述中的js代码
        $data['goods_desc'] = removeXSS($_POST['goods_desc']);

        // 上传文件至服务器
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $rs = uploadOne('logo', 'Goods', [
                'sm' => [50, 50],
                'mid' => [130, 130],
                'big' => [350, 350],
                'mbig' => [700, 700],
            ]);

            if ($rs['code']) {
                $this->error = $rs['msg'];
                return false;
            } else {
                $data += $rs['data'];
            }
        }

        // 获取插入表时的当前时间
        $data['addtime'] = date('Y-m-d H:i:s', time());

        return true;
    }

    /**
     * 添加完成之后调用的函数，插入记录的id会放进data中
     * @param $data
     * @param $options
     */
    protected function _after_insert($data, $options)
    {
        $memberPrices = I('post.member_price');
        foreach ($memberPrices as $k => $v) {
            D('member_price')->add([
                'price' => $v ?: $data['shop_price'],
                'level_id' => $k,
                'goods_id' => $data['id'],
            ]);
        }

        $goodsCats = array_unique(I('post.goods_cat'));
        foreach ($goodsCats as $v) {
            if (empty($v) || !is_numeric($v)) {
                continue;
            }
            D('goods_cat')->add([
                'cat_id' => $v,
                'goods_id' => $data['id'],
            ]);
        }

        $goodsAttrs = I('post.attr_value');
        foreach ($goodsAttrs as $k => $v) {
            if (is_array($v)) {
                $_v = array_unique($v);
                foreach ($_v as $_va) {
                    if (!empty($_va)) {
                        D('goods_attribute')->add([
                            'attr_id' => $k,
                            'attr_value' => $_va,
                            'goods_id' => $data['id'],
                        ]);
                    }
                }
            } else {
                if (!empty($v)) {
                    D('goods_attribute')->add([
                        'attr_id' => $k,
                        'attr_value' => $v,
                        'goods_id' => $data['id'],
                    ]);
                }
            }
        }
    }

    /**
     * 这个方法会在修改之前被调用（钩子函数）
     * @param $data array 表单中即将要插入到数据库中的数据（数组形式）
     * @param $options
     * @return false
     */
    protected function _before_update(&$data, $options)
    {
        // 过滤商品描述中的js代码
        $data['goods_desc'] = removeXSS($_POST['goods_desc']);

        // 上传文件至服务器
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $rs = uploadOne('logo', 'goods', [
                'sm' => [50, 50],
                'mid' => [130, 130],
                'big' => [350, 350],
                'mbig' => [700, 700],
            ]);

            if ($rs['code']) {
                $this->error = $rs['msg'];
                return false;
            } else {
                $data += $rs['data'];
            }

            // 删除服务器中旧图片
            deleteImage([
                'logo' => I('post.old_logo'),
                'sm_logo' => I('post.old_sm_logo'),
                'mid_logo' => I('post.old_mid_logo'),
                'big_logo' => I('post.old_big_logo'),
                'mbig_logo' => I('post.old_mbig_logo'),
            ]);
        }

        D('goods_cat')->where(['goods_id' => $options['where']['id']])->delete();
        $goodsCats = array_unique(I('post.goods_cat'));
        foreach ($goodsCats as $v) {
            if (empty($v) || !is_numeric($v)) {
                continue;
            }
            D('goods_cat')->add([
                'cat_id' => $v,
                'goods_id' => $options['where']['id'],
            ]);
        }

        // 修改商品属性
        $goodsAttrIds = I('post.goods_attr_id');
        $goodsAttrValues = I('post.attr_value');
        $index = 0;
        foreach ($goodsAttrValues as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $_v) {
                    if (!empty($_v)) {
                        if (empty($goodsAttrIds[$index])) {
                            D('goods_attribute')->add([
                                'goods_id' => $options['where']['id'],
                                'attr_id' => $k,
                                'attr_value' => $_v,
                            ]);
                        } else {
                            D('goods_attribute')->where(['id' => $goodsAttrIds[$index]])->setField('attr_value', $_v);
                        }
                        $index++;
                    } else {
                        $index++;
                    }
                }
            } else {
                if (!empty($_v)) {
                    if (empty($goodsAttrIds[$index])) {
                        D('goods_attribute')->add([
                            'goods_id' => $options['where']['id'],
                            'attr_id' => $k,
                            'attr_value' => $v,
                        ]);
                    } else {
                        D('goods_attribute')->where(['id' => $goodsAttrIds[$index]])->setField('attr_value', $v);
                    }
                    $index++;
                } else {
                    $index++;
                }
            }
        }

        return true;
    }

    /**
     * 这个方法会在删除之前被调用（钩子函数）
     * @param $options
     * @return false
     */
    protected function _before_delete($options)
    {
        if (is_array($options['where']['id'])) {
            $this->error = '不支持批量删除';
            return false;
        }

        deleteImage($this->field('logo,sm_logo,mid_logo,big_logo,mbig_logo')->find($options['where']['id']));

        D('goods_cat')->where(['goods_id' => $options['where']['id']])->delete();

        D('goods_attribute')->where(['goods_id' => $options['where']['id']])->delete();

        return true;
    }

    /**
     * 取出一个分类下所有商品的id
     */
    public function getGoodsIdByCatId($catId)
    {
        $catIds = [];
        D('category')->getChildren($catIds, $catId);
        $catId = [$catId];
        if (!empty($catIds)) {
            $catId = array_merge($catId, array_column($catIds, 'id'));
        }

        $goodIds = $this->field('id')->where(['cat_id' => ['in', $catId]])->select() ?: [];
        $goodsCats = D('goods_cat')->field('DISTINCT goods_id as id')->where(['cat_id' => ['in', $catId]])->select() ?: [];

        return array_unique(array_column(array_merge($goodIds, $goodsCats), 'id'));
    }

    /**
     * 搜索、翻页、排序
     * @param int $perPage
     */
    public function search($perPage = 20)
    {
        $where = [];

        if ($goods_name = I('get.goods_name')) {
            $where['a.goods_name'] = array('like', "%$goods_name%");
        }

        $market_pricefrom = I('get.market_pricefrom');
        $market_priceto = I('get.market_priceto');
        if ($market_pricefrom && $market_priceto) {
            $where['a.market_price'] = array('between', array($market_pricefrom, $market_priceto));
        } elseif ($market_pricefrom) {
            $where['a.market_price'] = array('egt', $market_pricefrom);
        } elseif ($market_priceto) {
            $where['a.market_price'] = array('elt', $market_priceto);
        }

        $shop_pricefrom = I('get.shop_pricefrom');
        $shop_priceto = I('get.shop_priceto');
        if ($shop_pricefrom && $shop_priceto) {
            $where['a.shop_price'] = array('between', array($shop_pricefrom, $shop_priceto));
        } elseif ($shop_pricefrom) {
            $where['a.shop_price'] = array('egt', $shop_pricefrom);
        } elseif ($shop_priceto) {
            $where['a.shop_price'] = array('elt', $shop_priceto);
        }

        $is_on_sale = I('get.is_on_sale');
        if ($is_on_sale != '' && $is_on_sale != '-1') {
            $where['a.is_on_sale'] = array('eq', $is_on_sale);
        }

        $is_delete = I('get.is_delete');
        if ($is_delete != '' && $is_delete != '-1') {
            $where['a.is_delete'] = array('eq', $is_delete);
        }

        if ($goods_desc = I('get.goods_desc')) {
            $where['a.goods_desc'] = array('like', "%$goods_desc%");
        }

        $addtimefrom = I('get.addtimefrom');
        $addtimeto = I('get.addtimeto');
        if ($addtimefrom && $addtimeto) {
            $where['a.addtime'] = array('between', array(strtotime("$addtimefrom 00:00:00"), strtotime("$addtimeto 23:59:59")));
        } elseif ($addtimefrom) {
            $where['a.addtime'] = array('egt', strtotime("$addtimefrom 00:00:00"));
        } elseif ($addtimeto) {
            $where['a.addtime'] = array('elt', strtotime("$addtimeto 23:59:59"));
        }

        if ($brand_id = I('get.brand_id')) {
            $where['a.brand_id'] = array('eq', $brand_id);
        }

        if ($cat_id = I('get.cat_id')) {
            $goodsIds = $this->getGoodsIdByCatId($cat_id);
            $where['a.id'] = array('in', $goodsIds);
        }

        // 配置翻页的样式
        $pageObj = new Page($this->alias('a')->where($where)->count(), $perPage);
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');

        return [
            'page' => $pageObj->show(),
            'data' => $this
                ->alias('a')
                ->field('a.*, b.brand_name, c.name as cat_name, GROUP_CONCAT(e.name SEPARATOR "<br />") as goods_cat')
                ->join('brands b on a.brand_id = b.id', 'LEFT')
                ->join('category c on a.cat_id = c.id', 'LEFT')
                ->join('goods_cat d on a.id = d.goods_id', 'LEFT')
                ->join('category e on e.id = d.cat_id', 'LEFT')
                ->where($where)
                ->group('a.id')
                ->limit($pageObj->firstRow . ',' . $pageObj->listRows)
                ->select(),
        ];
    }
}
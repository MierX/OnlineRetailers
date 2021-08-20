<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class goodsModel extends Model
{

    protected $insertFields = 'goods_name,market_price,shop_price,is_on_sale,is_delete,goods_desc';

    protected $updateFields = 'id,goods_name,market_price,shop_price,is_on_sale,is_delete,goods_desc';

    protected $_validate = [
        ['goods_name', 'require', '商品名称不能为空！', 1, 'regex', 3],
        ['goods_name', '1,150', '商品名称的值最长不能超过 150 个字符！', 1, 'length', 3],
        ['market_price', 'require', '市场价格不能为空！', 1, 'regex', 3],
        ['market_price', 'currency', '市场价格必须是货币格式！', 1, 'regex', 3],
        ['shop_price', 'require', '本店价格不能为空！', 1, 'regex', 3],
        ['shop_price', 'currency', '本店价格必须是货币格式！', 1, 'regex', 3],
        ['is_on_sale', '是,否', "是否上架的值只能是在 '是,否' 中的一个值！", 2, 'in', 3],
        ['is_delete', '是,否', "是否放到回收站的值只能是在 '是,否' 中的一个值！", 2, 'in', 3],
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

        return true;
    }

    /**
     * 搜索、翻页、排序
     * @param int $perPage
     */
    public function search($perPage = 20)
    {
        $where = [];

        if ($goods_name = I('get.goods_name')) {
            $where['goods_name'] = array('like', "%$goods_name%");
        }

        $market_pricefrom = I('get.market_pricefrom');
        $market_priceto = I('get.market_priceto');
        if ($market_pricefrom && $market_priceto) {
            $where['market_price'] = array('between', array($market_pricefrom, $market_priceto));
        } elseif ($market_pricefrom) {
            $where['market_price'] = array('egt', $market_pricefrom);
        } elseif ($market_priceto) {
            $where['market_price'] = array('elt', $market_priceto);
        }

        $shop_pricefrom = I('get.shop_pricefrom');
        $shop_priceto = I('get.shop_priceto');
        if ($shop_pricefrom && $shop_priceto) {
            $where['shop_price'] = array('between', array($shop_pricefrom, $shop_priceto));
        } elseif ($shop_pricefrom) {
            $where['shop_price'] = array('egt', $shop_pricefrom);
        } elseif ($shop_priceto) {
            $where['shop_price'] = array('elt', $shop_priceto);
        }

        $is_on_sale = I('get.is_on_sale');
        if ($is_on_sale != '' && $is_on_sale != '-1') {
            $where['is_on_sale'] = array('eq', $is_on_sale);
        }

        $is_delete = I('get.is_delete');
        if ($is_delete != '' && $is_delete != '-1') {
            $where['is_delete'] = array('eq', $is_delete);
        }

        if ($goods_desc = I('get.goods_desc')) {
            $where['goods_desc'] = array('like', "%$goods_desc%");
        }

        $addtimefrom = I('get.addtimefrom');
        $addtimeto = I('get.addtimeto');
        if ($addtimefrom && $addtimeto) {
            $where['addtime'] = array('between', array(strtotime("$addtimefrom 00:00:00"), strtotime("$addtimeto 23:59:59")));
        } elseif ($addtimefrom) {
            $where['addtime'] = array('egt', strtotime("$addtimefrom 00:00:00"));
        } elseif ($addtimeto) {
            $where['addtime'] = array('elt', strtotime("$addtimeto 23:59:59"));
        }

        // 配置翻页的样式
        $pageObj = new Page($this->alias('a')->where($where)->count(), $perPage);
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');

        return [
            'page' => $pageObj->show(),
            'data' => $this->alias('a')->where($where)->group('a.id')->limit($pageObj->firstRow . ',' . $pageObj->listRows)->select(),
        ];
    }
}
<?php

namespace Admin\Model;

use Think\Image;
use Think\Model;
use Think\Page;
use Think\Upload;

class GoodsModel extends Model
{

    // 定义调用create方法允许接收的字段
    protected $insertFields = 'goods_name,market_price,shop_price,is_on_sale,goods_desc';

    // 定义调用save方法允许接收的字段
    protected $updateFields = 'id,goods_name,market_price,shop_price,is_on_sale,goods_desc';

    /**
     * 定义验证规则
     */
    protected $_validate = [
        ['goods_name', 'require', '商品名称不能为空', 1],
        ['market_price', 'currency', '市场价格必须是货币类型', 1],
        ['shop_price', 'currency', '本店价格必须是货币类型', 1],
    ];

    /**
     * 这个方法会在添加之前被调用（钩子函数）
     * @param $data array 表单中即将要插入到数据库中的数据（数组形式）
     * @param $options
     * @return false
     */
    protected function _before_insert(&$data, $options)
    {

        // 判断有没有上传图片
        if ($_FILES['logo']['error'] == 0) {
            $rs = uploadImage('logo', 'Goods', [
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

        // 过滤商品描述中的js代码
        $data['goods_desc'] = removeXSS($_POST['goods_desc']);

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

        // 判断有没有上传图片
        if ($_FILES['logo']['error'] == 0) {
            // 实例化上传类
            $upload = new Upload();

            // 最大图片尺寸1M
            $upload->maxSize = 1024 * 1024;

            // 设置文件上传类型
            $upload->exts = ['jpg', 'gif', 'png', 'jpeg'];

            // 设置文件上传根目录
            $upload->rootPath = './Public/Uploads/';

            // 设置文件上传目录
            $upload->savePath = 'Goods/';

            // 上传文件若失败则将错误信息保存到模型的error属性中，会被控制器调用
            $info = $upload->upload();
            if (!$info) {
                $this->error = $upload->getError();
                return false;
            } else {
                // 设置缩略图路径
                $data['logo'] = $info['logo']['savepath'] . $info['logo']['savename'];
                $data['sm_logo'] = $info['logo']['savepath'] . 'sm_' . $info['logo']['savename'];
                $data['mid_logo'] = $info['logo']['savepath'] . 'mid_' . $info['logo']['savename'];
                $data['big_logo'] = $info['logo']['savepath'] . 'big_' . $info['logo']['savename'];
                $data['mbig_logo'] = $info['logo']['savepath'] . 'mbig_' . $info['logo']['savename'];

                // 实例化图片类
                $image = new Image();

                // 打开要生成缩略图的图片
                $image->open('./Public/Uploads/' . $data['logo']);

                // 生成缩略图
                $image->thumb(700, 700)->save('./Public/Uploads/' . $data['mbig_logo']);
                $image->thumb(350, 350)->save('./Public/Uploads/' . $data['big_logo']);
                $image->thumb(130, 130)->save('./Public/Uploads/' . $data['mid_logo']);
                $image->thumb(50, 50)->save('./Public/Uploads/' . $data['sm_logo']);

                // 删除原来的图片
                $oldLogo = $this->field('logo,sm_logo,mid_logo,big_logo,mbig_logo')->find($options['where']['id']);
                deleteImage($oldLogo);
            }
        }

        // 过滤商品描述中的js代码
        $data['goods_desc'] = removeXSS($_POST['goods_desc']);

        return true;
    }

    /**
     * 这个方法会在删除之前被调用（钩子函数）
     * @param $options
     * @return false
     */
    protected function _before_delete($options)
    {

        // 删除原来的图片
        $oldLogo = $this->field('logo,sm_logo,mid_logo,big_logo,mbig_logo')->find($options['where']['id']);
        deleteImage($oldLogo);

        return true;
    }

    /**
     * 搜索、翻页、排序
     * @param int $perPage
     */
    public function search($perPage = 15)
    {

        $where = [];

        // 商品名称
        $goods_name = I('get.goods_name');
        if (!$goods_name) {
            $where['goods_name'] = ['like', "%$goods_name%"];
        }

        // 价格
        $fp = I('get.fp');
        $tp = I('get.tp');
        if ($fp && $tp) {
            $where['shop_price'] = ['between', [$fp, $tp]];
        } elseif ($fp) {
            $where['shop_price'] = ['egt', $fp];
        } elseif ($tp) {
            $where['shop_price'] = ['elt', $tp];
        }

        // 是否上架
        $ios = I('get.ios');
        if ($ios) {
            $where['is_on_sale'] = ['eq', $ios];
        }

        // 添加时间
        $fa = I('get.fa');
        $ta = I('get.ta');
        if ($fa && $ta) {
            $where['addtime'] = ['between', [$fa, $ta]];
        } elseif ($fa) {
            $where['addtime'] = ['egt', $fa];
        } elseif ($ta) {
            $where['addtime'] = ['elt', $ta];
        }

        // 排序
        $order = I('get.order', 'id desc');

        // 获取表中总记录数
        $count = $this->where($where)->count();

        // 生成翻页类的对象
        $pageObj = new Page($count, $perPage);

        // 设置翻页样式
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');

        // 生成页面下面显示的上一页、下一页的字符串
        $pageString = $pageObj->show();

        // 取得某一页的数据
        $data = $this->where($where)->order($order)->limit($pageObj->firstRow . ',' . $pageObj->listRows)->select();

        // 返回数据
        return [
            'data' => $data,
            'page' => $pageString
        ];
    }
}
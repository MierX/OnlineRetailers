<?php

namespace Admin\Model;

use Think\Image;
use Think\Model;
use Think\Upload;

class GoodsModel extends Model
{

    // 定义调用create方法允许接收的字段
    protected $insertFields = 'goods_name,market_price,shop_price,is_on_sale,goods_desc';

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
            }
        }

        // 过滤商品描述中的js代码
        $data['goods_desc'] = removeXSS($_POST['goods_desc']);

        // 获取插入表时的当前时间
        $data['addtime'] = date('Y-m-d H:i:s', time());
        
        return true;
    }
}
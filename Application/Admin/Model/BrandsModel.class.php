<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class brandsModel extends Model
{

    protected $insertFields = 'brand_name,brand_desc,site_url';

    protected $updateFields = 'id,brand_name,brand_desc,site_url';

    protected $_validate = [
        ['brand_name', 'require', '品牌名称不能为空！', 1, 'regex', 3],
        ['brand_name', '1,150', '品牌名称的值最长不能超过 150 个字符！', 1, 'length', 3],
        ['site_url', '1,150', '官方网址的值最长不能超过 150 个字符！', 2, 'length', 3],
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
        $data['brand_desc'] = removeXSS($_POST['brand_desc']);

        // 上传文件至服务器
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $rs = uploadOne('logo', 'Brands', [
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
        $data['brand_desc'] = removeXSS($_POST['brand_desc']);

        // 上传文件至服务器
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $rs = uploadOne('logo', 'brands', [
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

        if ($brand_name = I('get.brand_name')) {
            $where['brand_name'] = array('like', "%$brand_name%");
        }

        if ($brand_desc = I('get.brand_desc')) {
            $where['brand_desc'] = array('like', "%$brand_desc%");
        }

        if ($site_url = I('get.site_url')) {
            $where['site_url'] = array('like', "%$site_url%");
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
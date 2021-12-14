<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class BrandModel extends Model
{
    protected $insertFields = ['brand_name', 'site_url'];
    protected $updateFields = ['id', 'brand_name', 'site_url'];
    protected $_validate = [
        ['brand_name', 'require', '品牌名称不能为空！', 1, 'regex', 3],
        ['brand_name', '1,30', '品牌名称的值最长不能超过 30 个字符！', 1, 'length', 3],
        ['site_url', '1,150', '官方网址的值最长不能超过 150 个字符！', 2, 'length', 3],
    ];

    public function search($pageSize = 20)
    {
        /**************************************** 搜索 ****************************************/
        $where = [];
        if ($brand_name = I('get.brand_name')) {
            $where['brand_name'] = ['like', "%$brand_name%"];
        }

        /************************************* 翻页 ****************************************/
        $page = new Page($this->alias('a')->where($where)->count(), $pageSize);

        // 配置翻页的样式
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $data['page'] = $page->show();

        /************************************** 取数据 ******************************************/
        $data['data'] = $this->alias('a')->where($where)->group('a.id')->limit($page->firstRow . ',' . $page->listRows)->select();
        return $data;
    }

    // 添加前
    protected function _before_insert(&$data, $options)
    {
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $ret = uploadOne('logo', 'Brand', []);
            if ($ret['ok'] == 1) {
                $data['logo'] = $ret['images'][0];
            } else {
                $this->error = $ret['error'];
                return FALSE;
            }
        }
        return true;
    }

    // 修改前
    protected function _before_update(&$data, $options)
    {
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $ret = uploadOne('logo', 'Brand', []);
            if ($ret['ok'] == 1) {
                $data['logo'] = $ret['images'][0];
            } else {
                $this->error = $ret['error'];
                return FALSE;
            }
            deleteImage([I('post.old_logo')]);
        }
        return true;
    }

    // 删除前
    protected function _before_delete($options)
    {
        if (is_array($options['where']['id'])) {
            $this->error = '不支持批量删除';
            return FALSE;
        }
        $images = $this->field('logo')->find($options['where']['id']);
        deleteImage($images);
        return true;
    }
    /************************************ 其他方法 ********************************************/
}
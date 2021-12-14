<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class AttributeModel extends Model
{
    protected $insertFields = ['attr_name', 'attr_type', 'attr_option_values', 'type_id'];
    protected $updateFields = ['id', 'attr_name', 'attr_type', 'attr_option_values', 'type_id'];
    protected $_validate = [
        ['attr_name', 'require', '属性名称不能为空！', 1, 'regex', 3],
        ['attr_name', '1,30', '属性名称的值最长不能超过 30 个字符！', 1, 'length', 3],
        ['attr_type', 'require', '属性类型不能为空！', 1, 'regex', 3],
        ['attr_type', '唯一,可选', "属性类型的值只能是在 '唯一,可选' 中的一个值！", 1, 'in', 3],
        ['attr_option_values', '1,300', '属性可选值的值最长不能超过 300 个字符！', 2, 'length', 3],
        ['type_id', 'require', '所属类型Id不能为空！', 1, 'regex', 3],
        ['type_id', 'number', '所属类型Id必须是一个整数！', 1, 'regex', 3],
    ];

    public function search($pageSize = 20)
    {
        /**************************************** 搜索 ****************************************/
        $where = [];
        if ($attr_name = I('get.attr_name')) {
            $where['attr_name'] = ['like', "%$attr_name%"];
        }

        $attr_type = I('get.attr_type');
        if ($attr_type != '' && $attr_type != '-1') {
            $where['attr_type'] = ['eq', $attr_type];
        }
        if ($type_id = I('get.type_id')) {
            $where['type_id'] = ['eq', $type_id];
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
        // 把中文 逗号换成英文的
        $data['attr_option_values'] = str_replace('，', ',', $data['attr_option_values']);
    }

    // 修改前
    protected function _before_update(&$data, $options)
    {
    }

    // 删除前
    protected function _before_delete($options)
    {
        if (is_array($options['where']['id'])) {
            $this->error = '不支持批量删除';
            return FALSE;
        }
        return true;
    }
    /************************************ 其他方法 ********************************************/
}
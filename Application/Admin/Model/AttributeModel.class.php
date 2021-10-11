<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class AttributeModel extends Model
{

    protected $insertFields = 'attr_name,attr_type,attr_values,type_id';

    protected $updateFields = 'id,attr_name,attr_type,attr_values,type_id';

    protected $_validate = [
        ['attr_name', 'require', '属性名称不能为空！', 1, 'regex', 3],
        ['attr_name', '1,30', '属性名称的值最长不能超过 30 个字符！', 1, 'length', 3],
        ['attr_type', 'require', '属性类型不能为空！', 1, 'regex', 3],
        ['attr_type', '唯一,可选', "属性类型的值只能是在 '唯一,可选' 中的一个值！", 1, 'in', 3],
        ['attr_values', '1,300', '属性值的值最长不能超过 300 个字符！', 2, 'length', 3],
        ['type_id', 'require', '类型id不能为空！', 1, 'regex', 3],
        ['type_id', 'number', '类型id必须是一个整数！', 1, 'regex', 3],
    ];

    /**
     * 这个方法会在添加之前被调用（钩子函数）
     * @param $data array 表单中即将要插入到数据库中的数据（数组形式）
     * @param $options
     * @return false
     */
    protected function _before_insert(&$data, $options)
    {
        // 获取插入表时的当前时间
        $data['addtime'] = date('Y-m-d H:i:s', time());

        $data['attr_values'] = str_replace(',', '，', $data['attr_values']);

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
        $data['attr_values'] = str_replace(',', '，', $data['attr_values']);

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

    }

    /**
     * 搜索、翻页、排序
     * @param int $perPage
     */
    public function search($perPage = 20)
    {
        $where = [];

        if ($attr_name = I('get.attr_name')) {
            $where['a.attr_name'] = array('like', "%$attr_name%");
        }

        $attr_type = I('get.attr_type');
        if ($attr_type != '' && $attr_type != '-1') {
            $where['a.attr_type'] = array('eq', $attr_type);
        }

        if ($attr_values = I('get.attr_values')) {
            $where['a.attr_values'] = array('like', "%$attr_values%");
        }

        if ($type_id = I('get.type_id')) {
            $where['a.type_id'] = array('eq', $type_id);
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

        // 配置翻页的样式
        $pageObj = new Page($this->alias('a')->where($where)->count(), $perPage);
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');

        return [
            'page' => $pageObj->show(),
            'data' => $this
                ->field('a.*, b.type_name')
                ->alias('a')
                ->join('type b on b.id = a.type_id', 'LEFT')
                ->where($where)
                ->group('a.id')
                ->limit($pageObj->firstRow . ',' . $pageObj->listRows)
                ->select(),
        ];
    }
}
<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class TypeModel extends Model
{

    protected $insertFields = 'type_name';

    protected $updateFields = 'id,type_name';

    protected $_validate = [
        ['type_name', 'require', '类型名称不能为空！', 1, 'regex', 3],
        ['type_name', 'require', '类型名称不能为空！', 1, 'regex', 3],
        ['type_name', '', '类型名称已经存在！', 1, 'unique', 3],
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

        D('attribute')->where(['type_id' => $options['where']['id']])->delete();

        return true;
    }

    /**
     * 搜索、翻页、排序
     * @param int $perPage
     */
    public function search($perPage = 20)
    {
        $where = [];

        if ($type_name = I('get.type_name')) {
            $where['type_name'] = array('like', "%$type_name%");
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
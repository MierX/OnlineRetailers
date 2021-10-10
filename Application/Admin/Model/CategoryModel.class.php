<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class CategoryModel extends Model
{

    protected $insertFields = 'name,pid';

    protected $updateFields = 'id,name,pid';

    protected $_validate = [
        ['name', '1,30', '分类名称的值最长不能超过 30 个字符！', 2, 'length', 3],
        ['pid', 'number', '上级分类id必须是一个整数！', 2, 'regex', 3],
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
     */
    protected function _before_delete($options)
    {
        $data = [];
        $this->getChildren($data, $options['where']['id']);
        if (!empty($data)) {
            $options['where']['id'] = ['IN', implode(',', array_column($data, 'id')) . ",{$options['where']['id']}"];
        }
    }

    /**
     * 搜索、翻页、排序
     * @param int $perPage
     */
    public function search($perPage = 20)
    {
        $where = [];

        if ($name = I('get.name')) {
            $where['name'] = array('like', "%$name%");
        }

        if ($pid = I('get.pid')) {
            $where['pid'] = array('eq', $pid);
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

    /**
     * 找出一个分类所有子分类的id
     */
    public function getChildren(&$tree, $pid, $pname = '', $level = 0)
    {
        $data = $this->field('id, name')->where(['pid' => $pid])->select();

        foreach ($data as $v) {
            $tree[] = [
                'id' => $v['id'],
                'name' => ($level ? "&nbsp;" : '') . str_repeat("-&nbsp;", $level) . $v['name'],
                'pname' => $pname,
                'level' => $level,
            ];
            $this->getChildren($tree, $v['id'], $v['name'], $level + 1);
        }
    }
}
<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class MemberLevelModel extends Model
{

    protected $insertFields = 'level_name,integral_down,integral_up';

    protected $updateFields = 'id,level_name,integral_down,integral_up';

    protected $_validate = [
        ['level_name', 'require', '级别名称不能为空！', 1, 'regex', 3],
        ['level_name', '1,30', '级别名称的值最长不能超过 30 个字符！', 1, 'length', 3],
        ['integral_down', 'require', '积分下限不能为空！', 1, 'regex', 3],
        ['integral_down', 'number', '积分下限必须是一个整数！', 1, 'regex', 3],
        ['integral_up', 'require', '积分上限不能为空！', 1, 'regex', 3],
        ['integral_up', 'number', '积分上限必须是一个整数！', 1, 'regex', 3],
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

    }

    /**
     * 搜索、翻页、排序
     * @param int $perPage
     */
    public function search($perPage = 20)
    {
        $where = [];

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
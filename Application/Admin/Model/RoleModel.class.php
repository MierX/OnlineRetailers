<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class RoleModel extends Model
{

    protected $insertFields = 'role_name';

    protected $updateFields = 'id,role_name';

    protected $_validate = [
        ['role_name', 'require', '角色名称不能为空！', 1, 'regex', 3],
        ['role_name', '1,150', '角色名称的值最长不能超过 150 个字符！', 1, 'length', 3],
        ['role_name', '', '角色名称已经存在！', 1, 'unique', 3],
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
     * 这个方法会在添加之后被调用（钩子函数）
     * @param $data array 表单中即将要插入到数据库中的数据（数组形式）
     * @param $options
     * @return false
     */
    protected function _after_insert($data, $options)
    {
        $priIds = I('post.pri_id');
        foreach ($priIds as $v) {
            D('role_pri')->add([
                'pri_id' => $v,
                'role_id' => $data['id'],
            ]);
        }

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
            'data' => $this
                ->alias('a')
                ->field('a.*, GROUP_CONCAT(c.pri_name) as pri_name')
                ->join('role_pri b on b.role_id = a.id', 'LEFT')
                ->join('privilege c on c.id = b.pri_id', 'LEFT')
                ->where($where)
                ->group('a.id')
                ->limit($pageObj->firstRow . ',' . $pageObj->listRows)
                ->select(),
        ];
    }
}
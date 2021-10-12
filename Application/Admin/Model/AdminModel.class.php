<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class AdminModel extends Model
{

    protected $insertFields = 'username,password,c_password';

    protected $updateFields = 'id,username,password,c_password';

    protected $_validate = [
        ['username', 'require', '用户名不能为空！', 1, 'regex', 3],
        ['username', '1,150', '用户名的值最长不能超过 150 个字符！', 1, 'length', 3],
        ['username', '', '用户名已经存在！', 1, 'unique', 3],
        ['password', 'require', '密码不能为空！', 1, 'regex', 1],
        ['password', 'c_password', '两次密码必须一致！', 1, 'confirm', 3],
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

        // 加密密码
        $data['password'] = md5($data['password']);

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
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = md5($data['password']);
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

        if ($options['where']['id'] == 1) {
            $this->error = '无法删除超级管理员';
            return false;
        }

        return true;
    }

    /**
     * 搜索、翻页、排序
     * @param int $perPage
     */
    public function search($perPage = 20)
    {
        $where = [];

        if ($username = I('get.username')) {
            $where['username'] = array('like', "%$username%");
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
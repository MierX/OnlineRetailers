<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class RoleModel extends Model
{
    protected $insertFields = ['role_name'];
    protected $updateFields = ['id', 'role_name'];
    protected $_validate = [
        ['role_name', 'require', '角色名称不能为空！', 1, 'regex', 3],
        ['role_name', '1,30', '角色名称的值最长不能超过 30 个字符！', 1, 'length', 3],
        ['role_name', '', '角色名称已经存在！', 1, 'unique', 3],
    ];

    public function search($pageSize = 20)
    {
        /**************************************** 搜索 ****************************************/
        $where = [];

        /************************************* 翻页 ****************************************/
        $count = $this->alias('a')->where($where)->count();
        $page = new Page($count, $pageSize);

        // 配置翻页的样式
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $data['page'] = $page->show();

        /************************************** 取数据 ******************************************/
        $data['data'] = $this
            ->alias('a')
            ->field('a.*,GROUP_CONCAT(c.pri_name) pri_name')
            ->join('LEFT JOIN __ROLE_PRI__ b ON a.id=b.role_id')
            ->join('LEFT JOIN __PRIVILEGE__ c ON b.pri_id=c.id')
            ->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->group('a.id')
            ->select();
        return $data;
    }

    protected function _after_insert($data, $options)
    {
        $priId = I('post.pri_id');
        $rpModel = D('role_pri');
        foreach ($priId as $v) {
            $rpModel->add([
                'pri_id' => $v,
                'role_id' => $data['id'],
            ]);
        }
    }

    // 修改前
    protected function _before_update(&$data, $options)
    {
        /******** 处理拥有 的权限ID **********/
        $priId = I('post.pri_id');
        $rpModel = D('role_pri');
        $rpModel->where(['role_id' => ['eq', $options['where']['id']]])->delete();
        foreach ($priId as $v) {
            $rpModel->add([
                'pri_id' => $v,
                'role_id' => $options['where']['id'],
            ]);
        }
    }

    // 删除前
    protected function _before_delete($options)
    {
        // 从中间表中把这个权限相关的数据删除
        D('role_pri')->where(['role_id' => ['eq', $options['where']['id']]])->delete();
        D('admin_role')->where(['role_id' => ['eq', $options['where']['id']]])->delete();
    }
    /************************************ 其他方法 ********************************************/
}
<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class PrivilegeModel extends Model
{

    protected $insertFields = 'pri_name,module_name,controller_name,action_name,parent_id';

    protected $updateFields = 'id,pri_name,module_name,controller_name,action_name,parent_id';

    protected $_validate = [
        ['pri_name', 'require', '权限名称不能为空！', 1, 'regex', 3],
        ['pri_name', '1,150', '权限名称的值最长不能超过 150 个字符！', 1, 'length', 3],
//        ['module_name', 'require', '模块名称不能为空！', 1, 'regex', 3],
        ['module_name', '1,30', '模块名称的值最长不能超过 30 个字符！', 2, 'length', 3],
//        ['controller_name', 'require', '控制器名称不能为空！', 1, 'regex', 3],
        ['controller_name', '1,30', '控制器名称的值最长不能超过 30 个字符！', 2, 'length', 3],
        ['action_name', '1,30', '方法名称的值最长不能超过 30 个字符！', 2, 'length', 3],
        ['parent_id', 'number', '上级权限id必须是一个整数！', 2, 'regex', 3],
    ];

    /************************************* 递归相关方法 *************************************/
    public function getTree()
    {
        $data = $this->select();
        return $this->_reSort($data);
    }

    private function _reSort($data, $parent_id = 0, $level = 0, $isClear = true)
    {
        static $rs = array();
        if ($isClear)
            $rs = array();
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $v['level'] = $level;
                $rs[] = $v;
                $this->_reSort($data, $v['id'], $level + 1, false);
            }
        }
        return $rs;
    }

    public function getChildren($id)
    {
        $data = $this->select();
        return $this->_children($data, $id);
    }

    private function _children($data, $parent_id = 0, $isClear = true)
    {
        static $rs = array();
        if ($isClear)
            $rs = array();
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $rs[] = $v['id'];
                $this->_children($data, $v['id'], false);
            }
        }
        return $rs;
    }

    /************************************ 其他方法 ********************************************/
    public function _before_delete($options)
    {
        // 先找出所有的子分类
        $children = $this->getChildren($options['where']['id']);

        // 如果有子分类都删除掉
        if ($children) {
            $this->error = '有下级数据无法删除';
            return false;
        }

        return true;
    }
}
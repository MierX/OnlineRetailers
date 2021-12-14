<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class MemberLevelModel extends Model
{
    protected $insertFields = ['level_name', 'jifen_bottom', 'jifen_top'];
    protected $updateFields = ['id', 'level_name', 'jifen_bottom', 'jifen_top'];
    protected $_validate = [
        ['level_name', 'require', '级别名称不能为空！', 1, 'regex', 3],
        ['level_name', '1,30', '级别名称的值最长不能超过 30 个字符！', 1, 'length', 3],
        ['jifen_bottom', 'require', '积分下限不能为空！', 1, 'regex', 3],
        ['jifen_bottom', 'number', '积分下限必须是一个整数！', 1, 'regex', 3],
        ['jifen_top', 'require', '积分上限不能为空！', 1, 'regex', 3],
        ['jifen_top', 'number', '积分上限必须是一个整数！', 1, 'regex', 3],
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
        $data['data'] = $this->alias('a')->where($where)->group('a.id')->limit($page->firstRow . ',' . $page->listRows)->select();
        return $data;
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
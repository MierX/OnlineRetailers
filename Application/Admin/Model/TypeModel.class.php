<?php

namespace Admin\Model;

use Think\Model;
use Think\Page;

class TypeModel extends Model
{
    protected $insertFields = ['type_name'];
    protected $updateFields = ['id', 'type_name'];
    protected $_validate = [
        ['type_name', 'require', '类型名称不能为空！', 1, 'regex', 3],
        ['type_name', '1,30', '类型名称的值最长不能超过 30 个字符！', 1, 'length', 3],
        ['type_name', '', '类型名称已经存在！', 1, 'unique', 3],
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
        D('Attribute')->where(['type_id' => ['eq', $options['where']['id']]])->delete();
    }
    /************************************ 其他方法 ********************************************/
}
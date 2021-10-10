<?php
return [
    'tableName' => 'category', // 表名
    'tableCnName' => '商品分类', // 表的中文名
    'moduleName' => 'Admin', // 代码生成到的模块
    'withPrivilege' => false, // 是否生成相应权限的数据
    'topPriName' => '', // 顶级权限的名称
    'digui' => 0, // 是否无限级（递归）
    'diguiName' => '', // 递归时用来显示的字段的名字，如cat_name（分类名称）
    'pk' => 'id', // 表中主键字段名称
    'insertFields' => "'name,pid'", // 添加时允许接收的表单中的字段（要生成的模型文件中的代码）
    'updateFields' => "'id,name,pid'", // 修改时允许接收的表单中的字段
    'validate' => "
    ['name', '1,30', '分类名称的值最长不能超过 30 个字符！', 2, 'length', 3],
    ['pid', 'number', '上级分类必须是一个整数！', 2, 'regex', 3],
",
    'fields' => [
        'name' => [
            'text' => '分类名称',
            'type' => 'text',
            'default' => '',
        ],
        'pid' => [
            'text' => '上级分类',
            'type' => 'text',
            'default' => '0',
        ],
    ], // 表中每个字段信息的配置
    'search' => [
        ['name', 'normal', '', 'like', '分类名称'],
            ['pid', 'normal', '', 'eq', '上级分类'],
            ['addtime', 'betweenTime', 'addtimefrom,addtimeto', '', '创建时间'],
        ], // 搜索字段的配置
];
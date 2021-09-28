<?php
return [
    'tableName' => 'member_level', // 表名
    'tableCnName' => '会员级别', // 表的中文名
    'moduleName' => 'Admin', // 代码生成到的模块
    'withPrivilege' => false, // 是否生成相应权限的数据
    'topPriName' => '', // 顶级权限的名称
    'digui' => 0, // 是否无限级（递归）
    'diguiName' => '', // 递归时用来显示的字段的名字，如cat_name（分类名称）
    'pk' => 'id', // 表中主键字段名称
    'insertFields' => "'level_name,integral_down,integral_up'", // 添加时允许接收的表单中的字段（要生成的模型文件中的代码）
    'updateFields' => "'id,level_name,integral_down,integral_up'", // 修改时允许接收的表单中的字段
    'validate' => "
        ['level_name', 'require', '级别名称不能为空！', 1, 'regex', 3],
    ['level_name', '1,30', '级别名称的值最长不能超过 30 个字符！', 1, 'length', 3],
        ['integral_down', 'require', '积分下限不能为空！', 1, 'regex', 3],
    ['integral_down', 'number', '积分下限必须是一个整数！', 1, 'regex', 3],
        ['integral_up', 'require', '积分上限不能为空！', 1, 'regex', 3],
    ['integral_up', 'number', '积分上限必须是一个整数！', 1, 'regex', 3],
",
    'fields' => [
        'level_name' => [
            'text' => '级别名称',
            'type' => 'text',
            'default' => '',
        ],
        'integral_down' => [
            'text' => '积分下限',
            'type' => 'text',
            'default' => '',
        ],
        'integral_up' => [
            'text' => '积分上限',
            'type' => 'text',
            'default' => '',
        ],
    ], // 表中每个字段信息的配置
];
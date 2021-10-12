<?php
return [
    'tableName' => 'privilege', // 表名
    'tableCnName' => '管理权限', // 表的中文名
    'moduleName' => 'Admin', // 代码生成到的模块
    'withPrivilege' => false, // 是否生成相应权限的数据
    'topPriName' => '', // 顶级权限的名称
    'digui' => 1, // 是否无限级（递归）
    'diguiName' => 'pri_name', // 递归时用来显示的字段的名字，如cat_name（分类名称）
    'pk' => 'id', // 表中主键字段名称
    'insertFields' => "'pri_name,module_name,controller_name,action_name,parent_id'", // 添加时允许接收的表单中的字段（要生成的模型文件中的代码）
    'updateFields' => "'id,pri_name,module_name,controller_name,action_name,parent_id'", // 修改时允许接收的表单中的字段
    'validate' => "
        ['pri_name', 'require', '权限名称不能为空！', 1, 'regex', 3],
    ['pri_name', '1,150', '权限名称的值最长不能超过 150 个字符！', 1, 'length', 3],
        ['module_name', 'require', '模块名称不能为空！', 1, 'regex', 3],
    ['module_name', '1,30', '模块名称的值最长不能超过 30 个字符！', 1, 'length', 3],
        ['controller_name', 'require', '控制器名称不能为空！', 1, 'regex', 3],
    ['controller_name', '1,30', '控制器名称的值最长不能超过 30 个字符！', 1, 'length', 3],
    ['action_name', '1,30', '方法名称的值最长不能超过 30 个字符！', 2, 'length', 3],
    ['parent_id', 'number', '上级权限id必须是一个整数！', 2, 'regex', 3],
",
    'fields' => [
        'pri_name' => [
            'text' => '权限名称',
            'type' => 'text',
            'default' => '',
        ],
        'module_name' => [
            'text' => '模块名称',
            'type' => 'text',
            'default' => '',
        ],
        'controller_name' => [
            'text' => '控制器名称',
            'type' => 'text',
            'default' => '',
        ],
        'action_name' => [
            'text' => '方法名称',
            'type' => 'text',
            'default' => '',
        ],
        'parent_id' => [
            'text' => '上级权限id',
            'type' => 'text',
            'default' => '0',
        ],
    ], // 表中每个字段信息的配置
    'search' => [
    ], // 搜索字段的配置
];
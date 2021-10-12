<?php
return [
    'tableName' => 'role', // 表名
    'tableCnName' => '管理角色', // 表的中文名
    'moduleName' => 'Admin', // 代码生成到的模块
    'withPrivilege' => false, // 是否生成相应权限的数据
    'topPriName' => '', // 顶级权限的名称
    'digui' => 0, // 是否无限级（递归）
    'diguiName' => '', // 递归时用来显示的字段的名字，如cat_name（分类名称）
    'pk' => 'id', // 表中主键字段名称
    'insertFields' => "'role_name'", // 添加时允许接收的表单中的字段（要生成的模型文件中的代码）
    'updateFields' => "'id,role_name'", // 修改时允许接收的表单中的字段
    'validate' => "
        ['role_name', 'require', '角色名称不能为空！', 1, 'regex', 3],
    ['role_name', '1,150', '角色名称的值最长不能超过 150 个字符！', 1, 'length', 3],
    ['role_name', '', '角色名称已经存在！', 1, 'unique', 3],
",
    'fields' => [
        'role_name' => [
            'text' => '角色名称',
            'type' => 'text',
            'default' => '',
        ],
    ], // 表中每个字段信息的配置
    'search' => [
    ], // 搜索字段的配置
];
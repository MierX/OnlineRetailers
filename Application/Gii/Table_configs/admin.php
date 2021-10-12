<?php
return [
    'tableName' => 'admin', // 表名
    'tableCnName' => '管理员', // 表的中文名
    'moduleName' => 'Admin', // 代码生成到的模块
    'withPrivilege' => false, // 是否生成相应权限的数据
    'topPriName' => '', // 顶级权限的名称
    'digui' => 0, // 是否无限级（递归）
    'diguiName' => '', // 递归时用来显示的字段的名字，如cat_name（分类名称）
    'pk' => 'id', // 表中主键字段名称
    'insertFields' => "'username,password,c_password'", // 添加时允许接收的表单中的字段（要生成的模型文件中的代码）
    'updateFields' => "'id,username,password,c_password'", // 修改时允许接收的表单中的字段
    'validate' => "
        ['username', 'require', '用户名不能为空！', 1, 'regex', 3],
    ['username', '1,150', '用户名的值最长不能超过 150 个字符！', 1, 'length', 3],
    ['username', '', '用户名已经存在！', 1, 'unique', 3],
        ['password', 'require', '密码不能为空！', 1, 'regex', 3],
    ['password', '1,32', '密码的值最长不能超过 32 个字符！', 1, 'length', 3],
    ['password', 'c_password', '两次密码必须一致！', 1, 'confirm', 3],
    ",
    'fields' => [
        'username' => [
            'text' => '用户名',
            'type' => 'text',
            'default' => '',
        ],
        'password' => [
            'text' => '密码',
            'type' => 'password',
            'default' => '',
        ],
        'c_password' => [
            'text' => '确认密码',
            'type' => 'password',
            'default' => '',
        ],
    ], // 表中每个字段信息的配置
    'search' => [
        ['username', 'normal', '', 'like', '用户名'],
    ], // 搜索字段的配置
];
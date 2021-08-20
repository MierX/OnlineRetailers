<?php
return [
    'tableName' => 'brands', // 表名
    'tableCnName' => '品牌', // 表的中文名
    'moduleName' => 'Admin', // 代码生成到的模块
    'withPrivilege' => false, // 是否生成相应权限的数据
    'topPriName' => '', // 顶级权限的名称
    'digui' => 0, // 是否无限级（递归）
    'diguiName' => '', // 递归时用来显示的字段的名字，如cat_name（分类名称）
    'pk' => 'id', // 表中主键字段名称
    'insertFields' => "'brand_name,brand_desc,site_url'", // 添加时允许接收的表单中的字段（要生成的模型文件中的代码）
    'updateFields' => "'id,brand_name,brand_desc,site_url'", // 修改时允许接收的表单中的字段
    'validate' => "
        ['brand_name', 'require', '品牌名称不能为空！', 1, 'regex', 3],
    ['brand_name', '1,150', '品牌名称的值最长不能超过 150 个字符！', 1, 'length', 3],
    ['site_url', '1,150', '官方网址的值最长不能超过 150 个字符！', 2, 'length', 3],
",
    'fields' => [
        'brand_name' => [
            'text' => '品牌名称',
            'type' => 'text',
            'default' => '',
        ],
        'brand_desc' => [
            'text' => '品牌说明',
            'type' => 'html',
        'save_fields' => 'brand_desc',
            'default' => '',
        ],
        'site_url' => [
            'text' => '官方网址',
            'type' => 'text',
            'default' => '',
        ],
        'logo' => [
            'text' => '品牌图片',
            'type' => 'file',
            'thumbs' => [
                [50, 50],
                [130, 130],
                [350, 350],
                [700, 700],
            ],
            'save_fields' => ['logo', 'sm_logo', 'mid_logo', 'big_logo', 'mbig_logo'],
            'default' => '',
        ],
    ], // 表中每个字段信息的配置
    'search' => [
        ['brand_name', 'normal', '', 'like', '品牌名称'],
            ['brand_desc', 'normal', '', 'like', '品牌说明'],
            ['site_url', 'normal', '', 'like', '官方网址'],
            ['addtime', 'betweenTime', 'addtimefrom,addtimeto', '', '添加时间'],
        ], // 搜索字段的配置
];
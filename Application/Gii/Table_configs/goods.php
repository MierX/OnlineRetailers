<?php
return [
    'tableName' => 'goods', // 表名
    'tableCnName' => '商品', // 表的中文名
    'moduleName' => 'Admin', // 代码生成到的模块
    'withPrivilege' => false, // 是否生成相应权限的数据
    'topPriName' => '', // 顶级权限的名称
    'digui' => 0, // 是否无限级（递归）
    'diguiName' => '', // 递归时用来显示的字段的名字，如cat_name（分类名称）
    'pk' => 'id', // 表中主键字段名称
    'insertFields' => "'goods_name,market_price,shop_price,is_on_sale,is_delete,goods_desc'", // 添加时允许接收的表单中的字段（要生成的模型文件中的代码）
    'updateFields' => "'id,goods_name,market_price,shop_price,is_on_sale,is_delete,goods_desc'", // 修改时允许接收的表单中的字段
    'validate' => "
        ['goods_name', 'require', '商品名称不能为空！', 1, 'regex', 3],
    ['goods_name', '1,150', '商品名称的值最长不能超过 150 个字符！', 1, 'length', 3],
        ['market_price', 'require', '市场价格不能为空！', 1, 'regex', 3],
    ['market_price', 'currency', '市场价格必须是货币格式！', 1, 'regex', 3],
        ['shop_price', 'require', '本店价格不能为空！', 1, 'regex', 3],
    ['shop_price', 'currency', '本店价格必须是货币格式！', 1, 'regex', 3],
    ['is_on_sale', '是,否', \"是否上架的值只能是在 '是,否' 中的一个值！\", 2, 'in', 3],
    ['is_delete', '是,否', \"是否放到回收站的值只能是在 '是,否' 中的一个值！\", 2, 'in', 3],
",
    'fields' => [
        'goods_name' => [
            'text' => '商品名称',
            'type' => 'text',
            'default' => '',
        ],
        'market_price' => [
            'text' => '市场价格',
            'type' => 'text',
            'default' => '',
        ],
        'shop_price' => [
            'text' => '本店价格',
            'type' => 'text',
            'default' => '',
        ],
        'is_on_sale' => [
            'text' => '是否上架',
            'type' => 'radio',
        'values' => [
                    '是' => '是',
                    '否' => '否',
                ],
            'default' => '是',
        ],
        'is_delete' => [
            'text' => '是否放到回收站',
            'type' => 'radio',
        'values' => [
                    '是' => '是',
                    '否' => '否',
                ],
            'default' => '否',
        ],
        'goods_desc' => [
            'text' => '商品描述',
            'type' => 'html',
        'save_fields' => 'goods_desc',
            'default' => '',
        ],
        'logo' => [
            'text' => '原图',
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
        ['goods_name', 'normal', '', 'like', '商品名称'],
            ['market_price', 'between', 'market_pricefrom,market_priceto', '', '市场价格'],
            ['shop_price', 'between', 'shop_pricefrom,shop_priceto', '', '本店价格'],
            ['is_on_sale', 'in', '是,否', '', '是否上架'],
            ['is_delete', 'in', '是,否', '', '是否放到回收站'],
            ['goods_desc', 'normal', '', 'like', '商品描述'],
            ['addtime', 'betweenTime', 'addtimefrom,addtimeto', '', '添加时间'],
        ], // 搜索字段的配置
];
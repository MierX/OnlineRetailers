return [
    'tableName' => '<?php echo $___v; ?>', // 表名
    'tableCnName' => '<?php echo str_replace('表', '', $_tableInfo[0]['Comment']); ?>', // 表的中文名
    'moduleName' => 'Admin', // 代码生成到的模块
    'withPrivilege' => false, // 是否生成相应权限的数据
    'topPriName' => '', // 顶级权限的名称
    'digui' => 0, // 是否无限级（递归）
    'diguiName' => '', // 递归时用来显示的字段的名字，如cat_name（分类名称）
<?php
$_fields_arr = array();
$_pk = 'id';
foreach ($_tableFields as $k => $v) {
    if ($v['Key'] == 'PRI') {
        $_pk = $v['Field'];
        continue;
    }
    if ($v['Field'] == 'addtime') {
        continue;
    }
    if (preg_match('/(image|logo|face|img|pic)/', $v['Field'])) {
        continue;
    }
    $_fields_arr[] = "'{$v['Field']}'";
}
$_fields_arr = implode(',', $_fields_arr);
?>
    'pk' => '<?php echo $_pk; ?>', // 表中主键字段名称
    'insertFields' => "<?php echo str_replace("','", ',', $_fields_arr); ?>", // 添加时允许接收的表单中的字段（要生成的模型文件中的代码）
    'updateFields' => "<?php echo str_replace("','", ',', "'$_pk',$_fields_arr"); ?>", // 修改时允许接收的表单中的字段
    'validate' => "
<?php foreach ($_tableFields as $k => $v):
$_chkTime = 2;
if ($v['Field'] == $_pk || $v['Field'] == 'addtime' || preg_match('/(image|logo|face|img|pic)/', $v['Field']))
continue;
if ($v['Null'] == 'NO' && $v['Default'] === null):$_chkTime = 1; ?>
        ['<?php echo $v['Field']; ?>', 'require', '<?php echo $v['Comment']; ?>不能为空！', <?php echo $_chkTime; ?>, 'regex', 3],
<?php endif;
if ($v['Field'] == 'email'): ?>
    ['<?php echo $v['Field']; ?>', 'email', '<?php echo $v['Comment']; ?>格式不正确！', <?php echo $_chkTime; ?>, 'regex', 3],
<?php endif;
if (strpos($v['Type'], 'int') !== false): ?>
    ['<?php echo $v['Field']; ?>', 'number', '<?php echo $v['Comment']; ?>必须是一个整数！', <?php echo $_chkTime; ?>, 'regex', 3],
<?php endif;
if (strpos($v['Type'], 'decimal') !== false): ?>
    ['<?php echo $v['Field']; ?>', 'currency', '<?php echo $v['Comment']; ?>必须是货币格式！', <?php echo $_chkTime; ?>, 'regex', 3],
<?php endif;
if (strpos($v['Type'], 'enum') !== false): ?>
    ['<?php echo $v['Field']; ?>', <?php $_s1 = str_replace(array('enum(', ')', "','"), array('', '', ','), $v['Type']);
echo $_s1; ?>, \"<?php echo $v['Comment']; ?>的值只能是在 <?php echo $_s1; ?> 中的一个值！\", <?php echo $_chkTime; ?>, 'in', 3],
<?php endif;
if (strpos($v['Type'], 'varchar') === 0): ?>
    ['<?php echo $v['Field']; ?>', '1,<?php $_s1 = str_replace(array('varchar(', ')'), array('', ''), $v['Type']);
echo $_s1; ?>', '<?php echo $v['Comment']; ?>的值最长不能超过 <?php echo $_s1; ?> 个字符！', <?php echo $_chkTime; ?>, 'length', 3],
<?php endif;
if (strpos($v['Type'], 'char') === 0): ?>
    ['<?php echo $v['Field']; ?>', '1,<?php $_s1 = str_replace(array('char(', ')'), array('', ''), $v['Type']);
echo $_s1; ?>', '<?php echo $v['Comment']; ?>的值最长不能超过 <?php echo $_s1; ?> 个字符！', <?php echo $_chkTime; ?>, 'length', 3],
    <?php endif;
endforeach; ?>
<?php foreach ($_tableFields as $k => $v):
$_chkTime = 2;
if ($v['Field'] == $_pk)
continue;
if ($v['Null'] == 'NO' && $v['Default'] === null)
$_chkTime = 1;
if ($v['Key'] == 'UNI'): ?>
array('<?php echo $v['Field']; ?>', '', '<?php echo $v['Comment']; ?>的值已经存在，不能重复添加！', <?php echo $_chkTime; ?>, 'unique', 3),
<?php endif;
endforeach; ?>
",
    'fields' => [
<?php foreach ($_tableFields as $k => $v):
    if ($v['Field'] == $_pk || $v['Field'] == 'addtime' || preg_match('/(sm_|mid_|big_|mbig_)/', $v['Field'])) continue; ?>
        '<?php echo $v['Field']; ?>' => [
            'text' => '<?php echo $v['Comment']; ?>',
    <?php if (preg_match('/(image|logo|face|img|pic)/', $v['Field'])): ?>
        'type' => 'file',
            'thumbs' => [
                [50, 50],
                [130, 130],
                [350, 350],
                [700, 700],
            ],
            'save_fields' => ['<?php echo $v['Field']; ?>', 'sm_<?php echo $v['Field']; ?>', 'mid_<?php echo $v['Field']; ?>', 'big_<?php echo $v['Field']; ?>', 'mbig_<?php echo $v['Field']; ?>'],
<?php elseif (stripos($v['Type'], 'text') !== false): ?>
        'type' => 'html',
        'save_fields' => '<?php echo $v['Field']; ?>',
<?php elseif (strpos($v['Type'], 'enum') !== false): ?>
        'type' => 'radio',
        'values' => [
        <?php $_arr = explode("','", $v['Type']);
        foreach ($_arr as $k1 => $v1):
            $_value = str_replace(array("enum('", "')"), array('', ''), $v1); ?>
            '<?php echo $_value; ?>' => '<?php echo $_value; ?>',
        <?php endforeach; ?>
        ],
<?php elseif ($v['Field'] == 'password'): ?>
        'type' => 'password',
<?php else: ?>
        'type' => 'text',
<?php endif; ?>
            'default' => '<?php echo $v['Default']; ?>',
        ],
<?php endforeach; ?>
    ], // 表中每个字段信息的配置
    'search' => [
<?php foreach ($_tableFields as $k => $v):
    if ($v['Field'] == $_pk || $v['Field'] == 'order_num' || preg_match('/(image|logo|face|img|pic)/', $v['Field'])) continue;
    if (strpos($v['Type'], 'char') !== false || strpos($v['Type'], 'text') !== false): ?>
        ['<?php echo $v['Field']; ?>', 'normal', '', 'like', '<?php echo $v['Comment']; ?>'],
    <?php elseif (strpos($v['Type'], 'enum') !== false): ?>
        ['<?php echo $v['Field']; ?>', 'in', <?php echo str_replace(array('enum(', ')', "','"), array('', '', ','), $v['Type']); ?>, '', '<?php echo $v['Comment']; ?>'],
    <?php elseif (strpos($v['Type'], 'decimal') !== false): ?>
        ['<?php echo $v['Field']; ?>', 'between', '<?php echo $v['Field']; ?>from,<?php echo $v['Field']; ?>to', '', '<?php echo $v['Comment']; ?>'],
    <?php elseif (strpos($v['Type'], 'time') !== false): ?>
        ['<?php echo $v['Field']; ?>', 'betweenTime', '<?php echo $v['Field']; ?>from,<?php echo $v['Field']; ?>to', '', '<?php echo $v['Comment']; ?>'],
    <?php else: ?>
        ['<?php echo $v['Field']; ?>', 'normal', '', 'eq', '<?php echo $v['Comment']; ?>'],
    <?php endif;endforeach; ?>
    ], // 搜索字段的配置
];
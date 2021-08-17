namespace <?php echo $config['moduleName']; ?>\Model;

use Think\Model;

class <?php echo $config['tableName']; ?>Model extends Model
{

    protected $insertFields = <?php echo implode(',', $config['insertFields']); ?>;

    protected $updateFields = <?php echo implode(',', $config['updateFields']); ?>;

    protected $_validate = [
        <?php echo $config['validate']; ?>
    ];

<?php if ($config['digui'] == 0): ?>
    /**
    * 这个方法会在添加之前被调用（钩子函数）
    * @param $data array 表单中即将要插入到数据库中的数据（数组形式）
    * @param $options
    * @return false
    */
    protected function _before_insert(&$data, $options)
    {
    <?php foreach ($config['_before_insert'] as $k => $v): ?>
        $data['<?php echo $k; ?>'] = <?php echo rtrim($v, ';'); ?>;

    <?php endforeach; ?>
    <?php
        foreach ($config['fields'] as $k => $v) {
            if ($v['type'] == 'file') {
    ?>
        if (isset($_FILES['<?php echo $k; ?>']) && $_FILES['<?php echo $k; ?>']['error'] == 0) {
            $rs = uploadOne('<?php echo $k; ?>', '<?php echo $config['tableName']; ?>', [
            <?php foreach ($v['thumbs'] as $k1 => $v1): ?>
                '<?php echo explode('_', $v['save_fields'][($k1 + 1)])[0]; ?>' => [<?php echo $v1[0]; ?>, <?php echo $v1[1]; ?>, <?php echo $v1[2]; ?>],
            <?php endforeach; ?>
            ]);

            if ($rs['code']) {
                $this->error = $rs['msg'];
                return false;
            } else {
                $data += $rs['data'];
            }
        }

    <?php
            } elseif ($v['type'] == 'html') {
    ?>
        // 过滤商品描述中的js代码
        $data['<?php echo $v["save_fields"]; ?>'] = removeXSS($_POST['<?php echo $v["save_fields"]; ?>']);
    <?php
            }
        }
    ?>
        // 获取插入表时的当前时间
        $data['addtime'] = date('Y-m-d H:i:s', time());

        return true;
    }

    /**
    * 这个方法会在修改之前被调用（钩子函数）
    * @param $data array 表单中即将要插入到数据库中的数据（数组形式）
    * @param $options
    * @return false
    */
    protected function _before_update(&$data, $options)
    {
    <?php foreach ($config['fields'] as $k => $v):if ($v['type'] == 'file'): ?>
        if (isset($_FILES['<?php echo $k; ?>']) && $_FILES['<?php echo $k; ?>']['error'] == 0) {
            $rs = uploadOne('<?php echo $k; ?>', '<?php echo $config['tableName']; ?>', [
            <?php foreach ($v['thumbs'] as $k1 => $v1): ?>
                '<?php echo explode('_', $v['save_fields'][($k1 + 1)])[0]; ?>' => [<?php echo $v1[0]; ?>, <?php echo $v1[1]; ?>, <?php echo $v1[2]; ?>],
            <?php endforeach; ?>
            ]);

            if ($rs['code']) {
                $this->error = $rs['msg'];
                return false;
            } else {
                $data += $rs['data'];
            }

            deleteImage([
            <?php foreach ($v['save_fields'] as $k1 => $v1): ?>
                '<?php echo $v1; ?>' => I('post.old_<?php echo $v1; ?>'),
            <?php endforeach; ?>
            ]);

        }
    <?php elseif ($v['type'] == 'html'): ?>
        // 过滤商品描述中的js代码
        $data['<?php echo $v["save_fields"]; ?>'] = removeXSS($_POST['<?php echo $v["save_fields"]; ?>']);

    <?php endif;endforeach; ?>
        return ture;
    }

    public function search($pageSize = 20)
    {
    /**************************************** 搜索 ****************************************/
    $where = array();
    <?php foreach ($config['search'] as $k => $v): ?>
        <?php if ($v[1] == 'normal'): ?>
            if ($<?php echo $v[0]; ?> = I('get.<?php echo $v[0]; ?>'))
            <?php if ($v[3] == 'like'): ?>
                $where['<?php echo $v[0]; ?>'] = array('like', "%$<?php echo $v[0]; ?>%");
            <?php else : ?>
                $where['<?php echo $v[0]; ?>'] = array('<?php echo $v[3]; ?>', $<?php echo $v[0]; ?>);
            <?php endif; ?>
        <?php elseif ($v[1] == 'in'): $_arr = str_replace(',', "','", $v[2]); ?>
            $<?php echo $v[0]; ?> = I('get.<?php echo $v[0]; ?>');
            if ($<?php echo $v[0]; ?> != '' && $<?php echo $v[0]; ?> != '-1')
            $where['<?php echo $v[0]; ?>'] = array('eq', $<?php echo $v[0]; ?>);
        <?php elseif (strpos($v[1], 'between') === 0) :
            $_arr = explode(',', $v[2]); ?>
            $<?php echo $_arr[0]; ?> = I('get.<?php echo $_arr[0]; ?>');
            $<?php echo $_arr[1]; ?> = I('get.<?php echo $_arr[1]; ?>');
            if ($<?php echo $_arr[0]; ?> && $<?php echo $_arr[1]; ?>)
            $where['<?php echo $v[0]; ?>'] = array('between', array(<?php if ($v[1] == 'betweenTime') echo "strtotime(\"\${$_arr[0]} 00:00:00\")"; else echo '$' . $_arr[0]; ?>, <?php if ($v[1] == 'betweenTime') echo "strtotime(\"\${$_arr[1]} 23:59:59\")"; else echo '$' . $_arr[1]; ?>));
            elseif ($<?php echo $_arr[0]; ?>)
            $where['<?php echo $v[0]; ?>'] = array('egt', <?php if ($v[1] == 'betweenTime') echo "strtotime(\"\${$_arr[0]} 00:00:00\")"; else echo '$' . $_arr[0]; ?>);
            elseif ($<?php echo $_arr[1]; ?>)
            $where['<?php echo $v[0]; ?>'] = array('elt', <?php if ($v[1] == 'betweenTime') echo "strtotime(\"\${$_arr[1]} 23:59:59\")"; else echo '$' . $_arr[1]; ?>);
        <?php endif;endforeach; ?>
    /************************************* 翻页 ****************************************/
    $count = $this->alias('a')->where($where)->count();
    $page = new \Think\Page($count, $pageSize);
    // 配置翻页的样式
    $page->setConfig('prev', '上一页');
    $page->setConfig('next', '下一页');
    $data['page'] = $page->show();
    /************************************** 取数据 ******************************************/
    $data['data'] = $this->alias('a')->where($where)->group('a.<?php echo $config['pk']; ?>')->limit($page->firstRow.','.$page->listRows)->select();
    return $data;
    }


    // 删除前
    protected function _before_delete($options)
    {
    if (is_array($options['where']['<?php echo $config['pk']; ?>'])) {
    $this->error = '不支持批量删除';
    return FALSE;
    }
    <?php if ($config['digui'] == 1): ?>
        $_count = $this->where('parent_id='.$options['where']['<?php echo $config['pk']; ?>'])->count();
        if ($_count >= 1)
        {
        $this->error = '有子级数据，无法删除';
        return FALSE;
        }
    <?php endif; ?>
    <?php foreach ($config['fields'] as $k => $v):if ($v['type'] == 'file'): ?>
        $images = $this->field('<?php echo implode(',', $v['save_fields']); ?>')->find($options['where']['<?php echo $config['pk']; ?>']);
        deleteImage($images);
    <?php endif;endforeach; ?>
    }
<?php endif; ?>


<?php if ($config['digui'] == 1): ?>
    /************************************* 递归相关方法 *************************************/
    public function getTree()
    {
    $data = $this->select();
    return $this->_reSort($data);
    }
    private function _reSort($data, $parent_id=0, $level=0, $isClear=TRUE)
    {
    static $rs = array();
    if ($isClear)
    $rs = array();
    foreach ($data as $k => $v)
    {
    if ($v['parent_id'] == $parent_id)
    {
    $v['level'] = $level;
    $rs[] = $v;
    $this->_reSort($data, $v['<?php echo $config['pk']; ?>'], $level+1, FALSE);
    }
    }
    return $rs;
    }
    public function getChildren($<?php echo $config['pk']; ?>)
    {
    $data = $this->select();
    return $this->_children($data, $<?php echo $config['pk']; ?>);
    }
    private function _children($data, $parent_id=0, $isClear=TRUE)
    {
    static $rs = array();
    if ($isClear)
    $rs = array();
    foreach ($data as $k => $v)
    {
    if ($v['parent_id'] == $parent_id)
    {
    $rs[] = $v['<?php echo $config['pk']; ?>'];
    $this->_children($data, $v['<?php echo $config['pk']; ?>'], FALSE);
    }
    }
    return $rs;
    }
<?php endif; ?>
/************************************ 其他方法 ********************************************/
<?php if ($config['digui'] == 1): ?>
    public function _before_delete($options)
    {
    // 先找出所有的子分类
    $children = $this->getChildren($options['where']['<?php echo $config['pk']; ?>']);
    // 如果有子分类都删除掉
    if ($children)
    {
    $this->error = '有下级数据无法删除';
    return FALSE;
    }
    }
<?php endif; ?>
}
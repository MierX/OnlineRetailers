
namespace <?php echo $config['moduleName']; ?>\Controller;

use Think\Controller;

class <?php echo $config['tableName']; ?>Controller extends Controller
{

    private $model;

    public function __construct()
    {
        parent::__construct();

        // 接收模型并保存到模型中
        $this->model = D('<?php echo $config['tableName']; ?>');
    }

    /**
     * 添加商品
     */
    public function add()
    {
        // 判断是否有表单提交
        if (IS_POST) {
            if ($this->model->create(I('post.'), 1)) {
                // 插入到数据库
                if ($this->model->add()) {
                    $this->success('添加成功！', U('lst?p=' . I('get.p')));
                    exit;
                }
            }
            // 显示失败信息
            $this->error($this->model->getError());
        }

        // 显示表单页面
    <?php if ($config['digui'] == 1): ?>
        $this->assign('parentData', $this->model->getTree());
    <?php endif; ?>
    $this->assign([
            '_page_btn_name' => '<?php echo $config['tableCnName']; ?>列表',
            '_page_title' => '添加<?php echo $config['tableCnName']; ?>页',
            '_page_btn_link' => U('lst'),
        ]);
        $this->display();
    }

    /**
     * 商品列表页
     */
    public function lst()
    {
    <?php if ($config['digui'] == 1): ?>
        $data = $this->model->getTree();
        $this->assign([
            'data' => $data,
        ]);
    <?php else: ?>
    // 返回数据和翻页
        $data = $this->model->search();

        // 显示列表页
        $this->assign($data);
    <?php endif; ?>
    $this->assign([
            '_page_btn_name' => '添加<?php echo $config['tableCnName']; ?>',
            '_page_title' => '<?php echo $config['tableCnName']; ?>列表页',
            '_page_btn_link' => U('add'),
        ]);
        $this->display();
    }

    /**
     * 修改商品
     */
    public function edit()
    {
        // 判断是否有表单提交
        if (IS_POST) {
            if ($this->model->create(I('post.'), 2)) {
                if ($this->model->save() !== false) {
                    $this->success('修改成功！', U('lst', ['p' => I('get.p', 1)]));
                    exit;
                }
            }
            // 显示失败信息
            $this->error($this->model->getError());
        }

        // 设置页面中的信息
        $this->assign('data', $this->model->find(I('get.<?php echo $config['pk']; ?>', 0)));
    <?php if ($config['digui'] == 1): ?>
        $this->assign([
            'parentData' => $this->model->getTree(),
            'children' => $this->model->getChildren($<?php echo $config['pk']; ?>),
        ]);
    <?php endif; ?>
    $this->assign([
            '_page_btn_name' => '<?php echo $config['tableCnName']; ?>列表',
            '_page_title' => '编辑<?php echo $config['tableCnName']; ?>页',
            '_page_btn_link' => U('lst'),
        ]);
        $this->display();
    }

    /**
     * 删除商品
     */
    public function del()
    {
        if ($this->model->delete(I('get.id', 0)) !== false) {
            $this->success('删除成功！', U('lst', ['p' => I('get.p'), 1]));
            exit;
        } else {
            $this->error('删除失败！原因：' . $this->model->getError());
        }
    }
}
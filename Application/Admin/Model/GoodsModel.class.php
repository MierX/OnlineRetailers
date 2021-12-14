<?php

namespace Admin\Model;

use SphinxClient;
use Think\Model;
use Think\Page;

class GoodsModel extends Model
{
    // 添加时调用create方法允许接收的字段
    protected $insertFields = 'goods_name,market_price,shop_price,is_on_sale,goods_desc,brand_id,cat_id,type_id,promote_price,promote_start_date,promote_end_date,is_new,is_best,is_hot,sort_num,is_floor';

    // 修改时调用create方法允许接收的字段
    protected $updateFields = 'id,goods_name,market_price,shop_price,is_on_sale,goods_desc,brand_id,cat_id,type_id,promote_price,promote_start_date,promote_end_date,is_new,is_best,is_hot,sort_num,is_floor';

    //定义验证规则
    protected $_validate = [
        ['cat_id', 'require', '必须选择主分类！', 1],
        ['goods_name', 'require', '商品名称不能为空！', 1],
        ['market_price', 'currency', '市场价格必须是货币类型！', 1],
        ['shop_price', 'currency', '本店价格必须是货币类型！', 1],
    ];

    // 这个方法在添加之前会自动被调用 --》 钩子方法
    // 第一个参数：表单中即将要插入到数据库中的数据->数组
    // &按引用传递：函数内部要修改函数外部传进来的变量必须按钮引用传递，除非传递的是一个对象,因为对象默认是按引用传递的
    protected function _before_insert(&$data, $options)
    {
        /**************** 处理LOGO *******************/
        // 判断有没有选择图片
        if ($_FILES['logo']['error'] == 0) {
            $ret = uploadOne('logo', 'Goods', [
                [700, 700],
                [350, 350],
                [130, 130],
                [50, 50],
            ]);
            $data['logo'] = $ret['images'][0];
            $data['mbig_logo'] = $ret['images'][1];
            $data['big_logo'] = $ret['images'][2];
            $data['mid_logo'] = $ret['images'][3];
            $data['sm_logo'] = $ret['images'][4];
        }

        // 获取当前时间并添加到表单中这样就会插入到数据库中
        $data['addtime'] = date('Y-m-d H:i:s', time());

        // 我们自己来过滤这个字段
        $data['goods_desc'] = removeXSS($_POST['goods_desc']);
    }

    protected function _before_update(&$data, $options)
    {
        $id = $options['where']['id'];  // 要修改的商品的ID

        // 标记商品被修改了需要重新创建索引
        $data['is_updated'] = 1;

        // 设置sphinx中的这条记录的is_updated属性为1
        require('./sphinxapi.php');
        $sph = new SphinxClient();
        $sph->SetServer('localhost', 9312);

        // 意思：把 id=$id 这件商品的 is_updated 属性更新成1
        $sph->UpdateAttributes('goods', ['is_updated'], [$id => [1]]);

        /************ 修改商品属性 *****************/
        $gaid = I('post.goods_attr_id');
        $attrValue = I('post.attr_value');
        $gaModel = D('goods_attr');
        $_i = 0;  // 循环次数
        foreach ($attrValue as $k => $v) {
            foreach ($v as $v1) {
                // 这个replace into可以实现同样的功能
                // replace into ：如果记录存在就修改，记录不存在就添加。以主键字段来判断一条记录是否存在
                //$gaModel->execute('REPLACE INTO p39_goods_attr VALUES("'.$gaid[$_i].'","'.$v1.'","'.$k.'","'.$id.'")');
                // 找这个属性值是否有id

                if ($gaid[$_i] == '')
                    $gaModel->add([
                        'goods_id' => $id,
                        'attr_id' => $k,
                        'attr_value' => $v1,
                    ]);
                else
                    $gaModel->where([
                        'id' => ['eq', $gaid[$_i]],
                    ])->setField('attr_value', $v1);

                $_i++;
            }
        }

        /************ 处理扩展分类 ***************/
        $ecid = I('post.ext_cat_id');
        $gcModel = D('goods_cat');

        // 先删除原分类数据
        $gcModel->where([
            'goods_id' => ['eq', $id],
        ])->delete();
        if ($ecid) {
            // 去重
            $ecid = array_unique($ecid);
            foreach ($ecid as $v) {
                if (empty($v))
                    continue;
                $gcModel->add([
                    'cat_id' => $v,
                    'goods_id' => $id,
                ]);
            }
        }

        /**************** 处理LOGO *******************/
        // 判断有没有选择图片
        if ($_FILES['logo']['error'] == 0) {
            $ret = uploadOne('logo', 'Goods', [
                [700, 700],
                [350, 350],
                [130, 130],
                [50, 50],
            ]);
            $data['logo'] = $ret['images'][0];
            $data['mbig_logo'] = $ret['images'][1];
            $data['big_logo'] = $ret['images'][2];
            $data['mid_logo'] = $ret['images'][3];
            $data['sm_logo'] = $ret['images'][4];

            /*************** 删除原来的图片 *******************/
            // 先查询出原来图片的路径
            $oldLogo = $this->field('logo,mbig_logo,big_logo,mid_logo,sm_logo')->find($id);
            deleteImage($oldLogo);
        }

        /************ 处理相册图片 *****************/
        if (isset($_FILES['pic'])) {
            $pics = [];
            foreach ($_FILES['pic']['name'] as $k => $v) {
                $pics[] = [
                    'name' => $v,
                    'type' => $_FILES['pic']['type'][$k],
                    'tmp_name' => $_FILES['pic']['tmp_name'][$k],
                    'error' => $_FILES['pic']['error'][$k],
                    'size' => $_FILES['pic']['size'][$k],
                ];
            }
            $_FILES = $pics;  // 把处理好的数组赋给$_FILES，因为uploadOne函数是到$_FILES中找图片
            $gpModel = D('goods_pic');

            // 循环每个上传
            foreach ($pics as $k => $v) {
                if ($v['error'] == 0) {
                    $ret = uploadOne($k, 'Goods', [
                        [650, 650],
                        [350, 350],
                        [50, 50],
                    ]);
                    if ($ret['ok'] == 1) {
                        $gpModel->add([
                            'pic' => $ret['images'][0],
                            'big_pic' => $ret['images'][1],
                            'mid_pic' => $ret['images'][2],
                            'sm_pic' => $ret['images'][3],
                            'goods_id' => $id,
                        ]);
                    }
                }
            }
        }

        /************ 处理会员价格 ****************/
        $mp = I('post.member_price');
        $mpModel = D('member_price');

        // 先删除原来的会员价格
        $mpModel->where([
            'goods_id' => ['eq', $id],
        ])->delete();
        foreach ($mp as $k => $v) {
            $_v = (float)$v;
            // 如果设置了会员价格就插入到表中
            if ($_v > 0) {
                $mpModel->add([
                    'price' => $_v,
                    'level_id' => $k,
                    'goods_id' => $id,
                ]);
            }
        }

        // 我们自己来过滤这个字段
        $data['goods_desc'] = removeXSS($_POST['goods_desc']);
    }

    protected function _before_delete($options)
    {
        $id = $options['where']['id'];   // 要删除的商品的ID

        /*********** 删除商品库存量 ***************/
        $gnModel = D('goods_number');
        $gnModel->where([
            'goods_id' => ['eq', $id],
        ])->delete();

        /************* 删除商品属性 ******************/
        $gaModel = D('goods_attr');
        $gaModel->where([
            'goods_id' => ['eq', $id],
        ])->delete();

        /************* 删除扩展分类 ******************/
        $gcModel = D('goods_cat');
        $gcModel->where([
            'goods_id' => ['eq', $id],
        ])->delete();

        /************** 删除相册中的图片 ********************/
        // 先从相册表中取出相册所在硬盘的路径
        $gpModel = D('goods_pic');
        $pics = $gpModel->field('pic,sm_pic,mid_pic,big_pic')->where([
            'goods_id' => ['eq', $id],
        ])->select();

        // 循环每个图片从硬盘上删除图片
        foreach ($pics as $v) {
            deleteImage($v);  //删除pic,sm_pic,mid_pic,big_pic四张
        }

        // 从数据库中把记录删除
        $gpModel->where([
            'goods_id' => ['eq', $id],
        ])->delete();

        /*************** 删除原来的图片 *******************/
        // 先查询出原来图片的路径
        $oldLogo = $this->field('logo,mbig_logo,big_logo,mid_logo,sm_logo')->find($id);
        deleteImage($oldLogo);

        /************* 删除会员价格 ******************/
        $mpModel = D('member_price');
        $mpModel->where([
            'goods_id' => ['eq', $id],
        ])->delete();
    }

    /**
     * 取出一个分类下所有商品的ID[即考虑主分类也考虑了扩展分类】
     * @param $catId
     * @return array
     */
    public function getGoodsIdByCatId($catId)
    {
        // 先取出所有子分类的ID
        $catModel = D('Admin/Category');
        $children = $catModel->getChildren($catId);

        // 和子分类放一起
        $children[] = $catId;

        /*************** 取出主分类或者扩展分类在这些分类中的商品 ****************/
        // 取出主分类下的商品ID
        $gids = $this->field('id')->where([
            'cat_id' => ['in', $children],
        ])->select();

        // 取出扩展分类下的商品的ID
        $gcModel = D('goods_cat');
        $gids1 = $gcModel->field('DISTINCT goods_id id')->where([
            'cat_id' => ['IN', $children]
        ])->select();

        // 把主分类的ID和扩展分类下的商品ID合并成一个二维数组【两个都不为空时合并，否则取出不为空的数组】
        if ($gids && $gids1) {
            $gids = array_merge($gids, $gids1);
        } elseif ($gids1) {
            $gids = $gids1;
        }

        // 二维转一维并去重
        $id = [];
        foreach ($gids as $v) {
            if (!in_array($v['id'], $id)) {
                $id[] = $v['id'];
            }
        }
        return $id;
    }

    /**
     * 实现翻页、搜索、排序
     */
    public function search($perPage = 5)
    {
        /*************** 搜索 ******************/
        $where = [];  // 空的where条件

        // 商品名称
        $gn = I('get.gn');
        if ($gn) {
            $where['a.goods_name'] = ['like', "%$gn%"];  // WHERE goods_name LIKE '%$gn%'
        }

        // 价格
        $fp = I('get.fp');
        $tp = I('get.tp');
        if ($fp && $tp) {
            $where['a.shop_price'] = ['between', [$fp, $tp]]; // WHERE shop_price BETWEEN $fp AND $tp
        } elseif ($fp) {
            $where['a.shop_price'] = ['egt', $fp];   // WHERE shop_price >= $fp
        } elseif ($tp) {
            $where['a.shop_price'] = ['elt', $tp];   // WHERE shop_price <= $fp
        }

        // 是否上架
        $ios = I('get.ios');
        if ($ios) {
            $where['a.is_on_sale'] = ['eq', $ios];  // WHERE is_on_sale = $ios
        }

        // 添加时间
        $fa = I('get.fa');
        $ta = I('get.ta');
        if ($fa && $ta) {
            $where['a.addtime'] = ['between', [$fa, $ta]]; // WHERE shop_price BETWEEN $fp AND $tp
        } elseif ($fa) {
            $where['a.addtime'] = ['egt', $fa];   // WHERE shop_price >= $fp
        } elseif ($ta) {
            $where['a.addtime'] = ['elt', $ta];   // WHERE shop_price <= $fp
        }

        // 品牌
        $brandId = I('get.brand_id');
        if ($brandId) {
            $where['a.brand_id'] = ['eq', $brandId];
        }

        // 分类的搜索[既有主分类也有扩展分类下的商品]
        $catId = I('get.cat_id');
        if ($catId) {
            // 先查询出这个分类ID下所有的商品ID
            $gids = $this->getGoodsIdByCatId($catId);
            // 应用到取数据的WHERE上
            $where['a.id'] = ['in', $gids];
        }

        /*************** 翻页 ****************/
        // 生成翻页类的对象
        $pageObj = new Page($this->where($where)->count(), $perPage);

        // 设置样式
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('prev', '上一页');

        // 生成页面下面显示的上一页、下一页的字符串
        $pageString = $pageObj->show();

        /***************** 排序 *****************/
        $orderby = 'a.id';      // 默认的排序字段 
        $orderway = 'desc';   // 默认的排序方式
        $odby = I('get.odby');
        if ($odby) {
            if ($odby == 'id_asc') {
                $orderway = 'asc';
            } elseif ($odby == 'price_desc') {
                $orderby = 'shop_price';
            } elseif ($odby == 'price_asc') {
                $orderby = 'shop_price';
                $orderway = 'asc';
            }
        }

        /************** 取某一页的数据 ***************/
        /**
         * SELECT a.*,b.brand_name FROM p39_goods a LEFT JOIN p39_brand b ON a.brand_id=b.id
         */
        $data = $this
            ->order("$orderby $orderway")
            ->field('a.*,b.brand_name,c.cat_name,GROUP_CONCAT(e.cat_name SEPARATOR "<br />") ext_cat_name')
            ->alias('a')
            ->join('LEFT JOIN __BRAND__ b ON a.brand_id=b.id')
            ->join('LEFT JOIN __CATEGORY__ c ON a.cat_id=c.id')
            ->join('LEFT JOIN __GOODS_CAT__ d ON a.id=d.goods_id')
            ->join('LEFT JOIN __CATEGORY__ e ON d.cat_id=e.id')
            ->where($where)
            ->limit($pageObj->firstRow . ',' . $pageObj->listRows)
            ->group('a.id')
            ->select();

        /************** 返回数据 ******************/
        return [
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        ];
    }

    /**
     * 商品添加之后会调用这个方法，其中$data['id']就是新添加的商品的ID
     */
    protected function _after_insert($data, $options)
    {
        /************ 处理商品属性的代码 **************/
        $attrValue = I('post.attr_value');
        $gaModel = D('goods_attr');
        foreach ($attrValue as $k => $v) {
            // 把属性值的数组去重
            $v = array_unique($v);
            foreach ($v as $v1) {
                $gaModel->add([
                    'goods_id' => $data['id'],
                    'attr_id' => $k,
                    'attr_value' => $v1,
                ]);
            }
        }


        /************ 处理扩展分类 ***************/
        $ecid = I('post.ext_cat_id');
        if ($ecid) {
            $gcModel = D('goods_cat');
            foreach ($ecid as $v) {
                if (empty($v))
                    continue;
                $gcModel->add([
                    'cat_id' => $v,
                    'goods_id' => $data['id'],
                ]);
            }
        }

        /************ 处理相册图片 *****************/
        if (isset($_FILES['pic'])) {
            $pics = [];
            foreach ($_FILES['pic']['name'] as $k => $v) {
                $pics[] = [
                    'name' => $v,
                    'type' => $_FILES['pic']['type'][$k],
                    'tmp_name' => $_FILES['pic']['tmp_name'][$k],
                    'error' => $_FILES['pic']['error'][$k],
                    'size' => $_FILES['pic']['size'][$k],
                ];
            }

            $_FILES = $pics;  // 把处理好的数组赋给$_FILES，因为uploadOne函数是到$_FILES中找图片
            $gpModel = D('goods_pic');

            // 循环每个上传
            foreach ($pics as $k => $v) {
                if ($v['error'] == 0) {
                    $ret = uploadOne($k, 'Goods', [
                        [650, 650],
                        [350, 350],
                        [50, 50],
                    ]);
                    if ($ret['ok'] == 1) {
                        $gpModel->add([
                            'pic' => $ret['images'][0],
                            'big_pic' => $ret['images'][1],
                            'mid_pic' => $ret['images'][2],
                            'sm_pic' => $ret['images'][3],
                            'goods_id' => $data['id'],
                        ]);
                    }
                }
            }
        }

        /************ 处理会员价格 ****************/
        $mp = I('post.member_price');
        $mpModel = D('member_price');
        foreach ($mp as $k => $v) {
            $_v = (float)$v;
            // 如果设置了会员价格就插入到表中
            if ($_v > 0) {
                $mpModel->add([
                    'price' => $_v,
                    'level_id' => $k,
                    'goods_id' => $data['id'],
                ]);
            }
        }
    }

    /**
     * 取出当前正在促销的商品
     * @param int $limit
     * @return GoodsModel
     */
    public function getPromoteGoods($limit = 5)
    {
        $today = date('Y-m-d H:i');
        return $this
            ->field('id,goods_name,mid_logo,promote_price')
            ->where([
                'is_on_sale' => ['eq', '是'],
                'promote_price' => ['gt', 0],
                'promote_start_date' => ['elt', $today],
                'promote_end_date' => ['egt', $today],
            ])
            ->limit($limit)
            ->order('sort_num ASC')
            ->select();
    }

    /**
     * 取出三种推荐的数据
     * @param $recType
     * @param int $limit
     * @return GoodsModel
     */
    public function getRecGoods($recType, $limit = 5)
    {
        return $this
            ->field('id,goods_name,mid_logo,shop_price')
            ->where([
                'is_on_sale' => ['eq', '是'],
                "$recType" => ['eq', '是'],
            ])
            ->limit($limit)
            ->order('sort_num ASC')
            ->select();
    }

    /**
     * 获取会员价格
     * @param $goodsId
     * @return mixed
     */
    public function getMemberPrice($goodsId)
    {
        $today = date('Y-m-d H:i');
        $levelId = session('level_id');

        // 取出商品的促销价格
        $promotePrice = $this->field('promote_price')->where([
            'id' => ['eq', $goodsId],
            'promote_price' => ['gt', 0],
            'promote_start_date' => ['elt', $today],
            'promote_end_date' => ['egt', $today],
        ])->find();

        // 判断会员有没有登录
        if ($levelId) {
            $mpModel = D('member_price');
            $mpData = $mpModel
                ->field('price')
                ->where([
                    'goods_id' => ['eq', $goodsId],
                    'level_id' => ['eq', $levelId],
                ])
                ->find();

            // 这个级别有没有设置会员价格
            if ($mpData['price']) {
                if ($promotePrice['promote_price']) {
                    return min($promotePrice['promote_price'], $mpData['price']);
                } else {
                    return $mpData['price'];
                }
            } else {
                // 如果没有设置这个级别的价格就直接返回本店价格
                $p = $this->field('shop_price')->find($goodsId);
                if ($promotePrice['promote_price']) {
                    return min($promotePrice['promote_price'], $p['shop_price']);
                } else {
                    return $p['shop_price'];
                }
            }
        } else {
            $p = $this->field('shop_price')->find($goodsId);
            if ($promotePrice['promote_price']) {
                return min($promotePrice['promote_price'], $p['shop_price']);
            } else {
                return $p['shop_price'];
            }
        }
    }

    /**
     * 获取某个分类下某一页的商品
     * @param $catId
     * @param int $pageSize
     * @return array
     */
    public function cat_search($catId, $pageSize = 60)
    {
        /*************** 搜索 *************************/
        // 根据分类ID搜索出这个分类下商品的ID
        $goodsId = $this->getGoodsIdByCatId($catId);
        $where['a.id'] = ['in', $goodsId];

        // 品牌
        $brandId = I('get.brand_id');
        if ($brandId) {
            $where['a.brand_id'] = ['eq', (int)$brandId];
        }

        // 价格
        $price = I('get.price');
        if ($price) {
            $price = explode('-', $price);
            $where['a.shop_price'] = ['between', $price];
        }

        /******************************************* 商品搜索开始 ************************************************/
        $gaModel = D('goods_attr');
        $attrGoodsId = NULL;  // 根据每个属性搜索出来的商品的ID

        // 根据属性搜索 : 循环所有的参数找出属性的参数进行查询
        foreach ($_GET as $k => $v) {
            // 如果变量是以attr_开头的说明是一个属性的查询, 格式：attr_1/黑色-颜色
            if (strpos($k, 'attr_') === 0) {
                // 先解析出属性ID和属性值
                $attrId = str_replace('attr_', '', $k); // 属性id

                // 先取出最后一个-往后的字符串
                $attrName = strrchr($v, '-');
                $attrValue = str_replace($attrName, '', $v);

                // 根据属性ID和属性值搜索出这个属性值下的商品id：并返回一个字符串格式：1,2,3,4,5,6,7
                $gids = $gaModel
                    ->field('GROUP_CONCAT(goods_id) gids')
                    ->where([
                        'attr_id' => ['eq', $attrId],
                        'attr_value' => ['eq', $attrValue],
                    ])
                    ->find();

                // 判断是有商品
                if ($gids['gids']) {
                    $gids['gids'] = explode(',', $gids['gids']);
                    // 说明是搜索的第一个属性
                    if ($attrGoodsId === NULL) {
                        $attrGoodsId = $gids['gids'];  // 先暂存起来
                    } else {
                        // 和上一个属性搜索出来的结果求集
                        $attrGoodsId = array_intersect($attrGoodsId, $gids['gids']);

                        // 如果已经没有商品满足条件就不用再考虑下一个属性了
                        if (empty($attrGoodsId)) {
                            $where['a.id'] = ['eq', 0];
                            break;
                        }
                    }
                } else {
                    // 前几次的交集结果清空
                    $attrGoodsId = [];

                    // 如果这个属性下没有商品就应该向where中添加一个不可能满足的条件，这样后面取商品时就取不出来了！
                    $where['a.id'] = ['eq', 0];

                    break;
                }
            }
        }
        // 判断如果循环求次之后这个数组还不为空说明这些就是满足所有条件的商品id
        if ($attrGoodsId) {
            $where['a.id'] = ['IN', $attrGoodsId];
        }

        /******************************************* 商品搜索结束 ************************************************/


        /**************** 翻页 *********************/
        // 取出总的记录数，以及所有的商品ID的字符串
        //$count = $this->alias('a')->where($where)->count();  // 这个只能取总记录数，改成下面这行，即取总记录数，又取出了商品ID
        $count = $this->alias('a')->field('COUNT(a.id) goods_count,GROUP_CONCAT(a.id) goods_id')->where($where)->find();

        // 把商品ID返回
        $data['goods_id'] = explode(',', $count['goods_id']);

        $page = new Page($count['goods_count'], $pageSize);

        // 配置翻页的样式
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $data['page'] = $page->show();

        /*********************** 排序 ********************/
        $oderby = 'xl';    // 默认
        $oderway = 'desc'; // 默认
        $odby = I('get.odby');
        if ($odby) {
            if ($odby == 'addtime')
                $oderby = 'a.addtime';
            if (strpos($odby, 'price_') === 0) {
                $oderby = 'a.shop_price';
                if ($odby == 'price_asc')
                    $oderway = 'asc';
            }
        }

        /**************** 取数据 ********************/
        $data['data'] = $this->alias('a')
            ->field('a.id,a.goods_name,a.mid_logo,a.shop_price,SUM(b.goods_number) xl')
            ->join('LEFT JOIN __ORDER_GOODS__ b 
				 ON (a.id=b.goods_id 
				      AND 
				     b.order_id IN(SELECT id FROM __ORDER__ WHERE pay_status="是"))')
            ->where($where)
            ->group('a.id')
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order("$oderby $oderway")
            ->select();

        return $data;
    }

    /**
     * 获取某个关键字下某一页的商品
     * @param $key
     * @param int $pageSize
     * @return array
     */
    public function key_search($key, $pageSize = 60)
    {
        /*************** 搜索 *************************/

        //$goodsId = $this->getGoodsIdByCatId($catId);

        // 根据关键字【商品名称、商品描述、商品属性值】取出商品ID
        $goodsId = $this
            ->alias('a')
            ->field('GROUP_CONCAT(DISTINCT a.id) gids')
            ->join('LEFT JOIN __GOODS_ATTR__ b ON a.id=b.goods_id')
            ->where([
                'a.is_on_sale' => ['eq', '是'],
                'a.goods_name' => ['exp', " LIKE '%$key%' OR a.goods_desc LIKE '%$key%' OR attr_value LIKE '%$key%'"],
            ])
            ->find();
        $goodsId = explode(',', $goodsId['gids']);

        $where['a.id'] = ['in', $goodsId];

        // 品牌
        $brandId = I('get.brand_id');
        if ($brandId) {
            $where['a.brand_id'] = ['eq', (int)$brandId];
        }

        // 价格
        $price = I('get.price');
        if ($price) {
            $price = explode('-', $price);
            $where['a.shop_price'] = ['between', $price];
        }

        /******************************************* 商品搜索开始 ************************************************/
        $gaModel = D('goods_attr');
        $attrGoodsId = NULL;  // 根据每个属性搜索出来的商品的ID

        // 根据属性搜索 : 循环所有的参数找出属性的参数进行查询
        foreach ($_GET as $k => $v) {
            // 如果变量是以attr_开头的说明是一个属性的查询, 格式：attr_1/黑色-颜色
            if (strpos($k, 'attr_') === 0) {
                // 先解析出属性ID和属性值
                $attrId = str_replace('attr_', '', $k); // 属性id
                // 先取出最后一个-往后的字符串
                $attrName = strrchr($v, '-');
                $attrValue = str_replace($attrName, '', $v);
                // 根据属性ID和属性值搜索出这个属性值下的商品id：并返回一个字符串格式：1,2,3,4,5,6,7
                $gids = $gaModel
                    ->field('GROUP_CONCAT(goods_id) gids')
                    ->where([
                        'attr_id' => ['eq', $attrId],
                        'attr_value' => ['eq', $attrValue],
                    ])
                    ->find();
                // 判断是有商品

                if ($gids['gids']) {
                    $gids['gids'] = explode(',', $gids['gids']);
                    // 说明是搜索的第一个属性
                    if ($attrGoodsId === NULL) {
                        $attrGoodsId = $gids['gids'];  // 先暂存起来
                    } else {
                        // 和上一个属性搜索出来的结果求集
                        $attrGoodsId = array_intersect($attrGoodsId, $gids['gids']);
                        // 如果已经没有商品满足条件就不用再考虑下一个属性了
                        if (empty($attrGoodsId)) {
                            $where['a.id'] = ['eq', 0];
                            break;
                        }
                    }
                } else {
                    // 前几次的交集结果清空
                    $attrGoodsId = [];
                    // 如果这个属性下没有商品就应该向where中添加一个不可能满足的条件，这样后面取商品时就取不出来了！
                    $where['a.id'] = ['eq', 0];
                    // 取出循环，不用再查询下一个属性了
                    break;
                }
            }
        }
        // 判断如果循环求次之后这个数组还不为空说明这些就是满足所有条件的商品id
        if ($attrGoodsId)
            $where['a.id'] = ['IN', $attrGoodsId];
        /******************************************* 商品搜索结束 ************************************************/


        /**************** 翻页 *********************/
        // 取出总的记录数，以及所有的商品ID的字符串
        //$count = $this->alias('a')->where($where)->count();  // 这个只能取总记录数，改成下面这行，即取总记录数，又取出了商品ID
        $count = $this->alias('a')->field('COUNT(a.id) goods_count,GROUP_CONCAT(a.id) goods_id')->where($where)->find();
        // 把商品ID返回
        $data['goods_id'] = explode(',', $count['goods_id']);

        $page = new Page($count['goods_count'], $pageSize);

        // 配置翻页的样式
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $data['page'] = $page->show();

        /*********************** 排序 ********************/
        $oderby = 'xl';    // 默认
        $oderway = 'desc'; // 默认
        $odby = I('get.odby');
        if ($odby) {
            if ($odby == 'addtime')
                $oderby = 'a.addtime';
            if (strpos($odby, 'price_') === 0) {
                $oderby = 'a.shop_price';
                if ($odby == 'price_asc')
                    $oderway = 'asc';
            }
        }

        /**************** 取数据 ********************/
        $data['data'] = $this->alias('a')
            ->field('a.id,a.goods_name,a.mid_logo,a.shop_price,SUM(b.goods_number) xl')
            ->join('LEFT JOIN __ORDER_GOODS__ b ON (a.id=b.goods_id AND b.order_id IN(SELECT id FROM __ORDER__ WHERE pay_status="是"))')
            ->where($where)
            ->group('a.id')
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order("$oderby $oderway")
            ->select();

        return $data;
    }
}













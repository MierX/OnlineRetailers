<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="">
<head>
    <title>管理中心 - 商品列表 </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css"/>
    <link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="/Public/Umeditor/third-party/jquery.min.js"></script>
</head>
<body>
<h1>
     <span class="action-span">
        <a href="<?php echo ($_page_btn_link); ?>"><?php echo ($_page_btn_name); ?></a>
    </span>
    <span class="action-span1">
        <a href="/index.php/Admin/Index/index">管理中心</a>
    </span>
    <span id="search_id" class="action-span1"> - <?php echo ($_page_title); ?> </span>
    <div style="clear:both"></div>
</h1>

<!--  内容  -->

<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front" id="general-tab">通用信息</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form name="main_form" enctype="multipart/form-data" action="/index.php/Admin/Goods/edit?id=32" method="post">
            <input type="hidden" name="id" value="<?php echo I('get.id');?>"/>
            <table width="90%" id="general-table" align="center">
                <tr>
                    <td class="label">商品名称：</td>
                    <td>
                        <label>
                            <input type="text" name="goods_name" value="<?php echo ($data["goods_name"]); ?>" size="30"/>
                        </label>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">本店售价：</td>
                    <td>
                        <label>
                            <input type="text" name="shop_price" value="<?php echo ($data["shop_price"]); ?>" size="20"/>
                        </label>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <label>
                            <input type="radio" name="is_on_sale" value="是"
                            <?php if($data["is_on_sale"] == 是): ?>checked="checked"<?php endif; ?>
                            />
                            是
                        </label>
                        <label>
                            <input type="radio" name="is_on_sale" value="否"
                            <?php if($data["is_on_sale"] == 否): ?>checked="checked"<?php endif; ?>
                            />
                            否
                        </label>
                    </td>
                </tr>
                <tr>
                    <td class="label">市场售价：</td>
                    <td>
                        <label>
                            <input type="text" name="market_price" value="<?php echo ($data["market_price"]); ?>" size="20"/>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td class="label">LOGO：</td>
                    <td>
                        <img src="/Public/Uploads/<?php echo ($data["logo"]); ?>" alt=""/>
                        <input type="file" name="logo" size="60"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品简单描述：</td>
                    <td>
                        <textarea id="goods_desc" name="goods_desc"><?php echo ($data["goods_desc"]); ?></textarea>
                    </td>
                </tr>
            </table>
            <div class="button-div">
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button"/>
            </div>
        </form>
    </div>
</div>
<!--导入在线编辑器-->
<link href="/Public/Umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="/Public/Umeditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Umeditor/umeditor.min.js"></script>
<script type="text/javascript" src="/Public/Umeditor/lang/zh-cn/zh-cn.js"></script>
<script type="application/javascript">
    UM.getEditor('goods_desc', {
        initialFrameWidth: '100%',
        initialFrameHeight: 350
    })
</script>

<div id="footer"> www.or.com</div>
</body>
</html>
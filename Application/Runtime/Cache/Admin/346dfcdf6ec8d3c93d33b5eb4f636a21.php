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

<div class="main-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front">通用信息</span>
            <span class="tab-back">商品描述</span>
            <span class="tab-back">会员价格</span>
            <span class="tab-back">商品属性</span>
            <span class="tab-back">商品相册</span>
        </p>
    </div>
    <form name="main_form" method="POST" action="/index.php/Admin/Goods/add" enctype="multipart/form-data">
        <table cellspacing="1" cellpadding="3" width="100%" class="tab">
            <tr>
                <td class="label">所属品牌：</td>
                <td>
                    <?php echo buildSelect('brands', 'brand_id', 'id', 'brand_name', '');?>
                </td>
            </tr>
            <tr>
                <td class="label">商品名称：</td>
                <td>
                    <input type="text" name="goods_name" value=""/>
                </td>
            </tr>
            <tr>
                <td class="label">市场价格：</td>
                <td>
                    <input type="number" name="market_price" value=""/>
                </td>
            </tr>
            <tr>
                <td class="label">本店价格：</td>
                <td>
                    <input type="number" name="shop_price" value=""/>
                </td>
            <tr>
                <td class="label">是否上架：</td>
                <td>
                    <input type="radio" name="is_on_sale" value="是" checked="checked"/>
                    是 <input type="radio" name="is_on_sale" value="否"/>
                    否
                </td>
            </tr>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 "/>
                    <input type="reset" class="button" value=" 重置 "/>
                </td>
            </tr>
        </table>
        <table cellspacing="1" cellpadding="3" width="100%" class="tab" style="display: none">
            <tr>
                <td class='label'>商品描述</td>
                <td>
                    <textarea id='goods_desc' name='goods_desc'></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 "/>
                    <input type="reset" class="button" value=" 重置 "/>
                </td>
            </tr>
        </table>
        <table cellspacing="1" cellpadding="3" width="100%" class="tab" style="display: none">
            <?php if(is_array($level)): foreach($level as $key=>$lo): ?><tr>
                    <td class="label"><?php echo ($lo["level_name"]); ?>价格：</td>
                    <td>
                        <input type="number" name="member_price[<?php echo ($lo["id"]); ?>]" value=""/>
                    </td>
                </tr><?php endforeach; endif; ?>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 "/>
                    <input type="reset" class="button" value=" 重置 "/>
                </td>
            </tr>
        </table>
        <table cellspacing="1" cellpadding="3" width="100%" class="tab" style="display: none">
            <tr>
                <td class="label">是否放到回收站：</td>
                <td>
                    <input type="radio" name="is_delete" value="是"/>
                    是 <input type="radio" name="is_delete" value="否" checked="checked"/>
                    否
                </td>
            </tr>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 "/>
                    <input type="reset" class="button" value=" 重置 "/>
                </td>
            </tr>
        </table>
        <table cellspacing="1" cellpadding="3" width="100%" class="tab" style="display: none">
            <tr>
                <td class="label">原图：</td>
                <td>
                    <input type="file" name="logo"/>
                </td>
            </tr>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 "/>
                    <input type="reset" class="button" value=" 重置 "/>
                </td>
            </tr>
        </table>
    </form>
</div>
<!--导入在线编辑器-->
<link href="/Public/Umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="/Public/Umeditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Umeditor/umeditor.min.js"></script>
<script type="text/javascript" src="/Public/Umeditor/lang/zh-cn/zh-cn.js"></script>
<script type="application/javascript">
    UM.getEditor('goods_desc', {
        initialFrameWidth: "100%",
        initialFrameHeight: 350
    });

    $('#tabbar-div p span').click(function () {
        let i = $(this).index();
        $(this).removeClass('tab-back').addClass('tab-front').siblings().removeClass('tab-front').addClass('tab-back');
        $('.tab').eq(i).show().siblings().hide();
    })
</script>

<div id="footer"> www.or.com</div>
</body>
</html>
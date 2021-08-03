<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="">
<head>
    <title>ECSHOP 管理中心 - 添加新商品 </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css"/>
    <link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css"/>
    <link href="/Public/Umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="/Public/Umeditor/third-party/jquery.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/Umeditor/umeditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/Umeditor/umeditor.min.js"></script>
    <script type="text/javascript" src="/Public/Umeditor/lang/zh-cn/zh-cn.js"></script>
</head>
<body>
<h1>
    <span class="action-span">
        <a href="__GROUP__/Goods/goodsList">商品列表</a>
    </span>
    <span class="action-span1">
        <a href="__GROUP__">ECSHOP 管理中心</a>
    </span>
    <span id="search_id" class="action-span1"> - 添加新商品 </span>
    <div style="clear:both"></div>
</h1>
<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front" id="general-tab">通用信息</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="/index.php/Admin/Goods/add" method="post">
            <table width="90%" id="general-table" align="center">
                <tr>
                    <td class="label">商品名称：</td>
                    <td>
                        <label>
                            <input type="text" name="goods_name" value="" size="30"/>
                        </label>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <!--                <tr>-->
                <!--                    <td class="label">商品货号：</td>-->
                <!--                    <td>-->
                <!--                        <label>-->
                <!--                            <input type="text" name="goods_sn" value="" size="20"/>-->
                <!--                        </label>-->
                <!--                        <span id="goods_sn_notice"></span><br/>-->
                <!--                        <span class="notice-span" id="noticeGoodsSN">如果您不输入商品货号，系统将自动生成一个唯一的货号。</span>-->
                <!--                    </td>-->
                <!--                </tr>-->
                <!--                <tr>-->
                <!--                    <td class="label">商品分类：</td>-->
                <!--                    <td>-->
                <!--                        <label>-->
                <!--                            <select name="cat_id">-->
                <!--                                <option value="0">请选择...</option>-->
                <!--                                <?php if(is_array($cat_list)): foreach($cat_list as $key=>$val): ?>-->
                <!--                                    <option value="<<?php echo ($val["cat_id"]); ?>>"><<?php echo (str_repeat('&nbsp;&nbsp;',$val["lev"])); ?>><<?php echo ($val["cat_name"]); ?>></option>-->
                <!--<?php endforeach; endif; ?>-->
                <!--                            </select>-->
                <!--                        </label>-->
                <!--                        <span class="require-field">*</span>-->
                <!--                    </td>-->
                <!--                </tr>-->
                <!--                <tr>-->
                <!--                    <td class="label">商品品牌：</td>-->
                <!--                    <td>-->
                <!--                        <label>-->
                <!--                            <select name="brand_id">-->
                <!--                                <option value="0">请选择...</option>-->
                <!--                                <?php if(is_array($brand_list)): foreach($brand_list as $key=>$val): ?>-->
                <!--                                    <option value="<<?php echo ($val["brand_id"]); ?>>"><<?php echo ($val["brand_name"]); ?>></option>-->
                <!--<?php endforeach; endif; ?>-->
                <!--                            </select>-->
                <!--                        </label>-->
                <!--                    </td>-->
                <!--                </tr>-->
                <tr>
                    <td class="label">本店售价：</td>
                    <td>
                        <label>
                            <input type="text" name="shop_price" value="0" size="20"/>
                        </label>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <!--                <tr>-->
                <!--                    <td class="label">商品数量：</td>-->
                <!--                    <td>-->
                <!--                        <label>-->
                <!--                            <input type="text" name="goods_number" size="8" value=""/>-->
                <!--                        </label>-->
                <!--                    </td>-->
                <!--                </tr>-->
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <label>
                            <input type="radio" name="is_on_sale" value="1" checked="checked"/>
                            是
                        </label>
                        <label>
                            <input type="radio" name="is_on_sale" value="0"/>
                            否
                        </label>
                    </td>
                </tr>
                <!--                <tr>-->
                <!--                    <td class="label">加入推荐：</td>-->
                <!--                    <td>-->
                <!--                        <label>-->
                <!--                            <input type="checkbox" name="is_best" value="1"/>-->
                <!--                            精品-->
                <!--                        </label>-->
                <!--                        <label>-->
                <!--                            <input type="checkbox" name="is_new" value="1"/>-->
                <!--                            新品-->
                <!--                        </label>-->
                <!--                        <label>-->
                <!--                            <input type="checkbox" name="is_hot" value="1"/>-->
                <!--                            热销-->
                <!--                        </label>-->
                <!--                    </td>-->
                <!--                </tr>-->
                <!--                <tr>-->
                <!--                    <td class="label">推荐排序：</td>-->
                <!--                    <td>-->
                <!--                        <label>-->
                <!--                            <input type="text" name="sort_order" size="5" value="100"/>-->
                <!--                        </label>-->
                <!--                    </td>-->
                <!--                </tr>-->
                <tr>
                    <td class="label">市场售价：</td>
                    <td>
                        <label>
                            <input type="text" name="market_price" value="0" size="20"/>
                        </label>
                    </td>
                </tr>
                <!--                <tr>-->
                <!--                    <td class="label">商品关键词：</td>-->
                <!--                    <td>-->
                <!--                        <label>-->
                <!--                            <input type="text" name="keywords" value="" size="40"/>-->
                <!--                            用空格分隔-->
                <!--                        </label>-->
                <!--                    </td>-->
                <!--                </tr>-->
                <tr>
                    <td class="label">LOGO：</td>
                    <td>
                        <input type="file" name="logo" size="60"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品简单描述：</td>
                    <td>
                        <textarea id="goods_desc" name="goods_desc"></textarea>
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

<div id="footer">
    共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br/>
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
</div>
</body>
<!--导入在线编辑器-->
<script type="application/javascript">
    UM.getEditor('goods_desc', {
        initialFrameWidth: '100%',
        initialFrameHeight: 350
    })
</script>
</html>
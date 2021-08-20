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
        <form name="main_form" method="POST" action="/index.php/Admin/Brands/edit/id/1/p/1.html" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo I('get.id'); ?>"/>
            <input type="hidden" name="old_logo" value="<?php echo $data['logo']; ?>"/>
            <input type="hidden" name="old_sm_logo" value="<?php echo $data['sm_logo']; ?>"/>
            <input type="hidden" name="old_mid_logo" value="<?php echo $data['mid_logo']; ?>"/>
            <input type="hidden" name="old_big_logo" value="<?php echo $data['big_logo']; ?>"/>
            <input type="hidden" name="old_mbig_logo" value="<?php echo $data['mbig_logo']; ?>"/>
            <table width="90%" id="general-table" align="center">
                <tr>
                    <td class="label">
                        品牌名称：
                    </td>
                    <td>
                        <input type="text" name="brand_name" value="<?php echo $data['brand_name']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        品牌说明：
                    </td>
                    <td>
                                                            <textarea id="brand_desc" name="brand_desc">
                                    <?php echo $data['brand_desc']; ?>                                </textarea>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        官方网址：
                    </td>
                    <td>
                        <input type="text" name="site_url" value="<?php echo $data['site_url']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        品牌图片：
                    </td>
                    <td>
                        <input type="file" name="logo"/><br/>
                        <?php showImage($data['logo'], 100); ?>                                                    </td>
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
</div>

<link href="/Public/Umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="/Public/Umeditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Umeditor/umeditor.min.js"></script>
<script type="text/javascript" src="/Public/Umeditor/lang/zh-cn/zh-cn.js"></script>
<script type="application/javascript">
    UM.getEditor('brand_desc', {
        initialFrameWidth: "100%",
        initialFrameHeight: 350
    })
</script>

<div id="footer"> www.or.com</div>
</body>
</html>
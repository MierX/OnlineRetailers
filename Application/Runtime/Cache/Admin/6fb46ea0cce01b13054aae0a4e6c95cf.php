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

<div class="form-div">
    <form action="/index.php/Admin/Brands/lst" method="GET" name="searchForm">
        <img src="/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search"/>
        <p>
            品牌名称：
            <input type="text" name="brand_name" size="30" value="<?php echo I('get.brand_name'); ?>
            "/>
        </p>
        <p>
            品牌说明：
            <input type="text" name="brand_desc" size="30" value="<?php echo I('get.brand_desc'); ?>
            "/>
        </p>
        <p>
            官方网址：
            <input type="text" name="site_url" size="30" value="<?php echo I('get.site_url'); ?>
            "/>
        </p>
        <p>
            添加时间：
            从
            <input id="addtimefrom" type="text" name="addtimefrom" size="15"
                   value="<?php echo I('get.addtimefrom'); ?>"/>
            到
            <input id="addtimeto" type="text" name="addtimeto" size="15"
                   value="<?php echo I('get.addtimeto'); ?>"/>
        </p>
        <input type="submit" value=" 搜索 " class="button"/>
    </form>
</div>
<div class="list-div" id="listDiv">
    <table cellpadding="3" cellspacing="1">
        <tr>
            <th
            >品牌名称
            </th>
            <th
            >品牌说明
            </th>
            <th
            >官方网址
            </th>
            <th
            >品牌图片
            </th>
            <th width="60">操作</th>
        </tr>
        <?php foreach ($data as $k => $v): ?>
        <tr class="tron">
            <td align="center">
                <span><?php echo $v['brand_name']; ?></span>
            </td>
            <td align="center">
                <span><?php echo $v['brand_desc']; ?></span>
            </td>
            <td align="center">
                <span><?php echo $v['site_url']; ?></span>
            </td>
            <td align="center">
                <span><?php showImage($v['sm_logo']); ?></span>
            </td>
            <td align="center">
                <a href="<?php echo U('info?id='.$v['id'].'&p='.I('get.p')); ?>"
                   target="_blank" title="查看">
                    <img src="/Public/Admin/Images/icon_view.gif" width="16" height="16" border="0" alt=""/>
                </a>
                <a href="<?php echo U('edit?id='.$v['id'].'&p='.I('get.p')); ?>"
                   title="编辑">
                    <img src="/Public/Admin/Images/icon_edit.gif" width="16" height="16" border="0" alt=""/>
                </a>
                <a href="<?php echo U('del?id='.$v['id'].'&p='.I('get.p')); ?>"
                   onclick="return confirm('确定要删除吗')" title="删除">
                    <img src="/Public/Admin/Images/icon_trash.gif" width="16" height="16" border="0" alt=""/>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>        <?php if(preg_match('/\d/', $page)): ?>
        <tr>
            <td align="right" nowrap="true" colspan="99" height="30"><?php echo $page; ?></td>
        </tr>
        <?php endif; ?>            </table>
</div>
<link href="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/datepicker-zh_cn.js"></script>
<link rel="stylesheet" media="all" type="text/css"
      href="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.css"/>
<script type="text/javascript" src="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript"
        src="/Public/datetimepicker/time/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script>$.timepicker.setDefaults($.timepicker.regional['zh-CN']);</script>
<script>
    $('#addtimefrom').datetimepicker();
    $('#addtimeto').datetimepicker(); </script>
<script type="text/javascript" src="/Public/Admin/Js/tron.js"></script>

<div id="footer"> www.or.com</div>
</body>
</html>
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


<!-- 搜索 -->
<div class="form-div search_form_div">
    <form action="/index.php/Admin/Brands/lst" method="GET" name="search_form">
        <p>
            品牌名称：
            <input type="text" name="brand_name" size="30" value="<?php echo I('get.brand_name'); ?>"/>
        </p>
        <p>
            官方网址：
            <input type="text" name="site_url" size="30" value="<?php echo I('get.site_url'); ?>"/>
        </p>
        <p>
            添加时间：
            从 <input id="fa" type="text" name="fa" size="15" value="<?php echo I('get.fa'); ?>"/>
            到 <input id="ta" type="text" name="ta" size="15" value="<?php echo I('get.ta'); ?>"/>
        </p>
        <p><input type="submit" value=" 搜索 " class="button"/></p>
    </form>
</div>
<!-- 列表 -->
<div class="list-div" id="listDiv">
    <table cellpadding="3" cellspacing="1">
        <tr>
            <th>品牌名称</th>
            <th>官方网址</th>
            <th>品牌图片</th>
            <th width="60">操作</th>
        </tr>
        <?php foreach ($data as $k => $v): ?>
        <tr class="tron">
            <td><?php echo $v['brand_name']; ?></td>
            <td><?php echo $v['site_url']; ?></td>
            <td><?php echo $v['logo']; ?></td>
            <td align="center">
                <a href="<?php echo U('edit?id='.$v['id'].'&p='.I('get.p')); ?>" title="编辑">编辑</a> |
                <a href="<?php echo U('delete?id='.$v['id'].'&p='.I('get.p')); ?>" onclick="return confirm('确定要删除吗？');"
                   title="移除">移除</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if(preg_match('/\d/', $page)): ?>
        <tr>
            <td align="right" nowrap="true" colspan="99" height="30"><?php echo $page; ?></td>
        </tr>
        <?php endif; ?>
    </table>
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
    $('#fa').datetimepicker();
    $('#ta').datetimepicker(); </script>

<script src="/Public/Admin/Js/tron.js"></script>

<div id="footer"> www.or.com</div>
</body>
</html>
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

<div class="list-div" id="listDiv">
    <table cellpadding="3" cellspacing="1">
        <tr>
            <th width="80">角色名称
            </th>
            <th
            >权限列表
            </th>
            <th width="60">操作</th>
        </tr>
        <?php foreach ($data as $k => $v): ?>
        <tr class="tron">
            <td align="center">
                <span><?php echo $v['role_name']; ?></span>
            </td>
            <td align="center">
                <span><?php echo $v['pri_name']; ?></span>
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
<script>
</script>
<script type="text/javascript" src="/Public/Admin/Js/tron.js"></script>

<div id="footer"> www.or.com</div>
</body>
</html>
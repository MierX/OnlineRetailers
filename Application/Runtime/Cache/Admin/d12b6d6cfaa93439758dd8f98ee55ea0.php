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
    <form action="/index.php/Admin/Attribute/lst" method="GET" name="searchForm">
        <img src="/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search"/>
        <p>
            属性名称：
            <input type="text" name="attr_name" size="30" value="<?php echo I('get.attr_name'); ?>
            "/>
        </p>
        <p>
            属性类型：
            <input type="radio" value="-1"
                   name="attr_type" <?php if(I('get.attr_type', -1) == -1) echo 'checked="checked"'; ?>
            />
            全部
            <input type="radio" value="唯一"
                   name="attr_type" <?php if(I('get.attr_type', -1) == '唯一') echo 'checked="checked"'; ?>
            />
            唯一
            <input type="radio" value="可选"
                   name="attr_type" <?php if(I('get.attr_type', -1) == '可选') echo 'checked="checked"'; ?>
            />
            可选
        </p>
        <p>
            类型id：
            <?php echo buildSelect('type', 'type_id', 'id', 'type_name', I('get.type_id'));?>
        </p>
        <input type="submit" value=" 搜索 " class="button"/>
    </form>
</div>
<div class="list-div" id="listDiv">
    <table cellpadding="3" cellspacing="1">
        <tr>
            <th
            >属性名称
            </th>
            <th
            >属性类型
            </th>
            <th
            >属性值
            </th>
            <th
            >所属类型
            </th>
            <th width="60">操作</th>
        </tr>
        <?php foreach ($data as $k => $v): ?>
        <tr class="tron">
            <td align="center">
                <span><?php echo $v['attr_name']; ?></span>
            </td>
            <td align="center">
                <span><?php echo $v['attr_type']; ?></span>
            </td>
            <td align="center">
                <span><?php echo $v['attr_values']; ?></span>
            </td>
            <td align="center">
                <span><?php echo $v['type_name']; ?></span>
            </td>
            <td align="center">
                <a href="<?php echo U('info?id='.$v['id'].'&p='.I('get.p')); ?>"
                   target="_blank" title="查看">
                    <img src="/Public/Admin/Images/icon_view.gif" width="16" height="16" border="0" alt=""/>
                </a>
                <a href="<?php echo U('edit?id='.$v['id'].'&p='.I('get.p').'&type_id='.I('get.type_id')); ?>"
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
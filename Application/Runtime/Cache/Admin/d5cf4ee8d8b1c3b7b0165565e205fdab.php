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
        <form name="main_form" method="POST" action="/index.php/Admin/Privilege/edit/id/18.html" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo I('get.id'); ?>" />
                        <table width="90%" id="general-table" align="center">
                                    <tr>
                        <td class="label">上级权限：</td>
                        <td>
                            <select name="parent_id">
                                <option value="0">顶级权限</option>
                                <?php foreach ($parentData as $k => $v): ?>                                    <?php if($v['id'] == $data['id'] || in_array($v['id'], $children)) continue ; ?>                                    <option <?php if($v['id'] == $data['parent_id']): ?>selected="selected"<?php endif; ?> value="<?php echo $v['id']; ?>">
                                        <?php echo str_repeat('-', 8*$v['level']).$v['pri_name']; ?>                                    </option>
                                <?php endforeach; ?>                            </select>
                        </td>
                    </tr>
                                                    <tr>
                        <td class="label">
                            权限名称：
                        </td>
                        <td>
                                                            <input  type="text" name="pri_name" value="<?php echo $data['pri_name']; ?>" />
                                                    </td>
                    </tr>
                                    <tr>
                        <td class="label">
                            模块名称：
                        </td>
                        <td>
                                                            <input  type="text" name="module_name" value="<?php echo $data['module_name']; ?>" />
                                                    </td>
                    </tr>
                                    <tr>
                        <td class="label">
                            控制器名称：
                        </td>
                        <td>
                                                            <input  type="text" name="controller_name" value="<?php echo $data['controller_name']; ?>" />
                                                    </td>
                    </tr>
                                    <tr>
                        <td class="label">
                            方法名称：
                        </td>
                        <td>
                                                            <input  type="text" name="action_name" value="<?php echo $data['action_name']; ?>" />
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
</div>

<script type="application/javascript">
        </script>

<div id="footer"> www.or.com</div>
</body>
</html>
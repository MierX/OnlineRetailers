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
    <form name="main_form" method="POST" action="/index.php/Admin/Category/add.html">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">分类名称：</td>
                <td>
                    <input type="text" name="name" value=""/>
                </td>
            </tr>
            <tr>
                <td class="label">上级分类：</td>
                <td>
                    <select name="pid">
                        <option value="0">顶级分类</option>
                        <?php if(is_array($data)): foreach($data as $key=>$do): ?><option value="<?php echo ($do["id"]); ?>"><?php echo ($do["name"]); ?></option><?php endforeach; endif; ?>
                    </select>
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
<script type="application/javascript">
</script>

<div id="footer"> www.or.com</div>
</body>
</html>
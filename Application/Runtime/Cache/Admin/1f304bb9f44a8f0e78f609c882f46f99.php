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
    <form name="main_form" method="POST" action="/index.php/Admin/MemberLevel/add.html" enctype="multipart/form-data">
        <table cellspacing="1" cellpadding="3" width="100%">
                                                <tr>
                <td class="label">级别名称：</td>
                <td>
                                        <input  type="text" name="level_name" value="" />
                                    </td>
            </tr>
                                                <tr>
                <td class="label">积分下限：</td>
                <td>
                                        <input  type="text" name="integral_down" value="" />
                                    </td>
            </tr>
                                                <tr>
                <td class="label">积分上限：</td>
                <td>
                                        <input  type="text" name="integral_up" value="" />
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
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
        <form name="main_form" method="POST" action="/index.php/Admin/Admin/edit/id/2.html" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo I('get.id'); ?>" />
                        <table width="90%" id="general-table" align="center">
                                                    <tr>
                        <td class="label">
                            用户名：
                        </td>
                        <td>
                                                            <input  type="text" name="username" value="<?php echo $data['username']; ?>" />
                                                    </td>
                    </tr>
                                    <tr>
                        <td class="label">
                            密码：
                        </td>
                        <td>
                                                            <input type="password" size="25" name="password"/>
                                                    </td>
                    </tr>
                                    <tr>
                        <td class="label">
                            确认密码：
                        </td>
                        <td>
                                                            <input type="password" size="25" name="c_password"/>
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
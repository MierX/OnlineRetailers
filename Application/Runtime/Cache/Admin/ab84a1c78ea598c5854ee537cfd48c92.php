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
    <form name="main_form" method="POST" action="/index.php/Admin/Role/add.html" enctype="multipart/form-data">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">角色名称：</td>
                <td>
                    <input type="text" name="role_name" value=""/>
                </td>
            </tr>
            <tr>
                <td class="label">权限列表：</td>
                <td>
                    <?php if(is_array($priData)): foreach($priData as $key=>$po): echo str_repeat('-', 8 * $po['level']);?>
                        <input type="checkbox" level_id="<?php echo ($po["level"]); ?>" name="pri_id[]" value="<?php echo ($po["id"]); ?>"/><?php echo ($po["pri_name"]); ?>
                        <br/><?php endforeach; endif; ?>
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
    // 为所有的复选框绑定一个事件
    $(':checkbox').click(function () {
        let level_id = $(this).attr('level_id');
        let tmp_level_id = level_id;

        // 判断是选中还是取消
        if ($(this).prop('checked')) {
            // 所有的子权限也选中
            $(this).nextAll(":checkbox").each(function (k, v) {
                if ($(v).attr('level_id') > level_id) {
                    $(v).attr('checked', 'checked');
                } else {
                    return false;
                }
            });

            // 所有的上级也选中
            $(this).prevAll(":checkbox").each(function (k, v) {
                if ($(v).attr('level_id') < tmp_level_id) {
                    $(v).attr('checked', 'checked');
                    tmp_level_id--;
                } else {
                    return false;
                }
            })
        } else {
            // 所有的子权限也取消
            $(this).nextAll(":checkbox").each(function (k, v) {
                if ($(v).attr('level_id') > level_id) {
                    $(v).attr('checked', false);
                } else {
                    return false;
                }
            });
        }
    });
</script>

<div id="footer"> www.or.com</div>
</body>
</html>
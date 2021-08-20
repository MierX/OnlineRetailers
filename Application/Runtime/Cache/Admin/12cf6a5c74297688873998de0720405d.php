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
    <form action="/index.php/Admin/Goods/lst" method="get" name="searchForm">
        <img src="/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search"/>
        <p>
            商品名称：
            <input type="text" name="goods_name" size="60" value="<?php echo I('get.goods_name');?>"/>
        </p>
        <p>
            价格：
            从
            <input type="text" name="fp" size="8" value="<?php echo I('get.fp');?>"/>
            到
            <input type="text" name="tp" size="8" value="<?php echo I('get.tp');?>"/>
        </p>
        <p>
            是否上架：
            <input type="radio" name="ios" size="8" value=""
            <?php if(I('get.ios') == ''): ?>checked="checked"<?php endif; ?>
            />
            全部
            <input type="radio" name="ios" size="8" value="是"
            <?php if(I('get.ios') == '是'): ?>checked="checked"<?php endif; ?>
            />
            上架
            <input type="radio" name="ios" size="8" value="否"
            <?php if(I('get.ios') == '否'): ?>checked="checked"<?php endif; ?>
            />
            下架
        </p>
        <p>
            添加时间：
            从
            <input type="text" id="fa" name="fa" size="20" value="<?php echo I('get.fa');?>"/>
            到
            <input type="text" id="ta" name="ta" size="20" value="<?php echo I('get.ta');?>"/>
        </p>
        <p>
            <?php echo $order=I('get.order', 'id desc');?>
            排序方式：
            <input type="radio" name="order" value="id desc" onclick="this.parentNode.parentNode.submit()"
            <?php if($order == 'id desc'): ?>checked="checked"<?php endif; ?>
            />
            以id降序
            <input type="radio" name="order" value="id asc" onclick="this.parentNode.parentNode.submit()"
            <?php if($order == 'id asc'): ?>checked="checked"<?php endif; ?>
            />
            以id升序
            <input type="radio" name="order" value="shop_price desc" onclick="this.parentNode.parentNode.submit()"
            <?php if($order == 'shop_price desc'): ?>checked="checked"<?php endif; ?>
            />
            以店内价格降序
            <input type="radio" name="order" value="shop_price asc" onclick="this.parentNode.parentNode.submit()"
            <?php if($order == 'shop_price asc'): ?>checked="checked"<?php endif; ?>
            />
            以店内价格升序
            <input type="radio" name="order" value="addtime desc" onclick="this.parentNode.parentNode.submit()"
            <?php if($order == 'addtime desc'): ?>checked="checked"<?php endif; ?>
            />
            以添加时间降序
            <input type="radio" name="order" value="addtime asc" onclick="this.parentNode.parentNode.submit()"
            <?php if($order == 'addtime asc'): ?>checked="checked"<?php endif; ?>
            />
            以添加时间升序
        </p>
        <input type="submit" value=" 搜索 " class="button"/>
    </form>
</div>
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>编号</th>
                <th>商品名称</th>
                <th>缩略图</th>
                <th>市场价格</th>
                <th>店内价格</th>
                <th>是否上架</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($data)): foreach($data as $key=>$val): ?><tr class="tron">
                    <td align="center"><?php echo ($val["id"]); ?></td>
                    <td align="center" class="first-cell">
                        <span><?php echo ($val["goods_name"]); ?></span>
                    </td>
                    <td align="center">
                        <?php echo showImage($val['sm_logo']);?>
                    </td>
                    <td align="center">
                        <span onclick=""><?php echo ($val["market_price"]); ?></span>
                    </td>
                    <td align="center">
                        <span><?php echo ($val["shop_price"]); ?></span>
                    </td>
                    <td align="center">
                        <img src="<?php if($val["is_on_sale"] == 是): ?>/Public/Admin/Images/yes.gif <?php else: ?> /Public/Admin/Images/no.gif<?php endif; ?>"
                             alt=""/>
                    </td>
                    <td align="center">
                        <span><?php echo ($val["addtime"]); ?></span>
                    </td>
                    <td align="center">
                        <a href="/index.php/Admin/Goods/info?id=<?php echo ($val["id"]); ?>" target="_blank" title="查看">
                            <img src="/Public/Admin/Images/icon_view.gif" width="16" height="16" border="0" alt=""/>
                        </a>
                        <a href="/index.php/Admin/Goods/edit?id=<?php echo ($val["id"]); ?>" title="编辑">
                            <img src="/Public/Admin/Images/icon_edit.gif" width="16" height="16" border="0" alt=""/>
                        </a>
                        <a href="/index.php/Admin/Goods/del?id=<?php echo ($val["id"]); ?>" onclick="return confirm('确定要删除吗')" title="删除">
                            <img src="/Public/Admin/Images/icon_trash.gif" width="16" height="16" border="0" alt=""/>
                        </a>
                    </td>
                </tr><?php endforeach; endif; ?>
        </table>

        <!-- 分页开始 -->
        <table id="page-table" cellspacing="0">
            <tr>
                <td width="80%">&nbsp;</td>
                <td align="center" nowrap="nowrap">
                    <?php echo ($page); ?>
                </td>
            </tr>
        </table>
        <!-- 分页结束 -->
    </div>
</form>
<link href="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/datepicker-zh_cn.js"></script>
<link rel="stylesheet" media="all" type="text/css"
      href="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.css"/>
<script type="text/javascript" src="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript"
        src="/Public/datetimepicker/time/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="application/javascript">
    $.timepicker.setDefaults($.timepicker.regional['zh-CN']);
    $('#fa').datetimepicker();
    $('#ta').datetimepicker();
</script>
<script type="text/javascript" src="/Public/Admin/Js/tron.js"></script>

<div id="footer"> www.or.com</div>
</body>
</html>
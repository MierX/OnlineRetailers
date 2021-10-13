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
    <form name="" method="POST" action="/index.php/Admin/Goods/goodsInventory/id/12.html">
        <table cellpadding="3" cellspacing="1">
            <input type="hidden" name="goods_id" value="<?php echo ($goods_id); ?>" />
            <tr>
                <?php if(is_array($data)): foreach($data as $dk=>$do): ?><th><?php echo ($dk); ?></th><?php endforeach; endif; ?>
                <th width="120">库存量</th>
                <th width="60">操作</th>
            </tr>
            <?php if(!empty($inventory)): if(is_array($inventory)): foreach($inventory as $ik=>$io): ?><tr class="tron">
                        <?php if(is_array($data)): foreach($data as $key=>$do): ?><td align="center">
                                <select name="attr_id[]">
                                    <option value="">请选择</option>
                                    <?php $attrIdArr = explode(',', $io['attr_id']) ?>
                                    <?php if(is_array($do)): foreach($do as $key=>$dv): if(in_array($dv['id'], $attrIdArr)): ?><option value="<?php echo ($dv['id']); ?>" selected="selected"><?php echo ($dv['attr_value']); ?></option>
                                            <?php else: ?>
                                            <option value="<?php echo ($dv['id']); ?>"><?php echo ($dv['attr_value']); ?></option><?php endif; endforeach; endif; ?>
                                </select>
                            </td><?php endforeach; endif; ?>
                        <td align="center"><input type="text" name="number[]" value="<?php echo ($io['number']); ?>" /></td>
                        <td align="center"><button type="button" onclick="addNewTr(this)">+</button></td>
                    </tr><?php endforeach; endif; ?>
            <?php else: ?>
                <tr class="tron">
                    <?php if(is_array($data)): foreach($data as $key=>$do): ?><td align="center">
                            <select name="attr_id[]">
                                <option value="">请选择</option>
                                <?php if(is_array($do)): foreach($do as $key=>$dv): ?><option value="<?php echo ($dv['id']); ?>"><?php echo ($dv['attr_value']); ?></option><?php endforeach; endif; ?>
                            </select>
                        </td><?php endforeach; endif; ?>
                    <td align="center"><input type="text" name="number[]" value="" /></td>
                    <td align="center"><button type="button" onclick="addNewTr(this)">+</button></td>
                </tr><?php endif; ?>
            <tr id="submit">
                <td align="center" colspan="<?php echo (count($data) + 2);?>"><button type="submit">提交</button></td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript" src="/Public/Admin/Js/tron.js"></script>
<script>
    function addNewTr(obj) {
        let tr = $(obj).parent().parent();
        if ($(obj).text() == '+') {
            let newTr = tr.clone();
            newTr.find('button').text('-');
            $('#submit').before(newTr);
        } else {
            tr.remove();
        }
    }
</script>

<div id="footer"> www.or.com</div>
</body>
</html>
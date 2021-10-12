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
<!--suppress JSJQueryEfficiency -->

<style>
    #goods_cat_lst li {
        margin: 5px;
    }
</style>

<div class="main-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front">通用信息</span>
            <span class="tab-back">商品描述</span>
            <span class="tab-back">会员价格</span>
            <span class="tab-back">商品属性</span>
            <span class="tab-back">商品相册</span>
        </p>
    </div>
    <form name="main_form" method="POST" action="/index.php/Admin/Goods/edit/id/8.html" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo I('get.id'); ?>"/>
        <input type="hidden" name="old_logo" value="<?php echo $data['logo']; ?>"/>
        <input type="hidden" name="old_sm_logo" value="<?php echo $data['sm_logo']; ?>"/>
        <input type="hidden" name="old_mid_logo" value="<?php echo $data['mid_logo']; ?>"/>
        <input type="hidden" name="old_big_logo" value="<?php echo $data['big_logo']; ?>"/>
        <input type="hidden" name="old_mbig_logo" value="<?php echo $data['mbig_logo']; ?>"/>
        <table cellspacing="1" cellpadding="3" width="100%" class="tab">
            <tr>
                <td class="label">所属品牌：</td>
                <td>
                    <?php echo buildSelect('brands', 'brand_id', 'id', 'brand_name', $data['brand_id']);?>
                </td>
            </tr>
            <tr>
                <td class="label">主分类：</td>
                <td>
                    <select name="cat_id">
                        <option value="">选择分类</option>
                        <?php if(is_array($catData)): foreach($catData as $key=>$co): ?><option value="<?php echo ($co["id"]); ?>"
                            <?php if(($co["id"]) == $data['cat_id']): ?>selected="selected"<?php endif; ?>
                            ><?php echo ($co["name"]); ?></option><?php endforeach; endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label">扩展分类</td>
                <td>
                    <ul id="goods_cat_lst">
                        <?php if(!empty($goodsCat)): if(is_array($goodsCat)): foreach($goodsCat as $key=>$go): ?><li>
                                    <select name="goods_cat[]">
                                        <option value="">选择分类</option>
                                        <?php if(is_array($catData)): foreach($catData as $key=>$co): ?><option value="<?php echo ($co["id"]); ?>"
                                            <?php if(($co["id"]) == $go['cat_id']): ?>selected="selected"<?php endif; ?>
                                            ><?php echo ($co["name"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </li><?php endforeach; endif; ?>
                            <?php else: ?>
                            <li>
                                <select name="goods_cat[]">
                                    <option value="">选择分类</option>
                                    <?php if(is_array($catData)): foreach($catData as $key=>$co): ?><option value="<?php echo ($co["id"]); ?>"><?php echo ($co["name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </li><?php endif; ?>
                    </ul>
                    <button onclick="$('#goods_cat_lst').append($('#goods_cat_lst').find('li').eq(0).clone());"
                            type="button" id="btn_add_cat">添加
                    </button>
                </td>
            </tr>
            <tr>
                <td class="label">
                    商品名称：
                </td>
                <td>
                    <input type="text" name="goods_name" value="<?php echo $data['goods_name']; ?>"/>
                </td>
            </tr>
            <tr>
                <td class="label">
                    市场价格：
                </td>
                <td>
                    <input type="text" name="market_price" value="<?php echo $data['market_price']; ?>"/>
                </td>
            </tr>
            <tr>
                <td class="label">
                    本店价格：
                </td>
                <td>
                    <input type="text" name="shop_price" value="<?php echo $data['shop_price']; ?>"/>
                </td>
            </tr>
            <tr>
                <td class="label">
                    是否上架：
                </td>
                <td>
                    <input type="radio" name="is_on_sale"
                           value="是" <?php if($data['is_on_sale'] == '是') echo 'checked="checked"'; ?> />
                    是 <input type="radio" name="is_on_sale"
                             value="否" <?php if($data['is_on_sale'] == '否') echo 'checked="checked"'; ?> />
                    否
                </td>
            </tr>
            <tr>
                <td class="label">
                    是否放到回收站：
                </td>
                <td>
                    <input type="radio" name="is_delete"
                           value="是" <?php if($data['is_delete'] == '是') echo 'checked="checked"'; ?> />
                    是 <input type="radio" name="is_delete"
                             value="否" <?php if($data['is_delete'] == '否') echo 'checked="checked"'; ?> />
                    否
                </td>
            </tr>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 "/>
                    <input type="reset" class="button" value=" 重置 "/>
                </td>
            </tr>
        </table>
        <table cellspacing="1" cellpadding="3" width="100%" class="tab" style="display: none">
            <tr>
                <td class="label">
                    商品描述：
                </td>
                <td>
                                                            <textarea id="goods_desc" name="goods_desc">
                                    <?php echo $data['goods_desc']; ?>                                </textarea>
                </td>
            </tr>
        </table>
        <table cellspacing="1" cellpadding="3" width="100%" class="tab" style="display: none">
            <?php if(is_array($level)): foreach($level as $key=>$lo): ?><tr>
                    <td class="label"><?php echo ($lo["level_name"]); ?>价格：</td>
                    <td>
                        <input type="number" name="member_price[<?php echo ($lo["id"]); ?>]" value="<?php echo ($member_price[$lo['id']]); ?>"/>
                    </td>
                </tr><?php endforeach; endif; ?>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 "/>
                    <input type="reset" class="button" value=" 重置 "/>
                </td>
            </tr>
        </table>
        <table cellspacing="1" cellpadding="3" width="100%" class="tab" style="display: none">
            <tr>
                <td class="label">类型：</td>
                <td>
                    <?php echo buildSelect('type', 'type_id', 'id', 'type_name', $data['type_id']);?>
                </td>
            </tr>
            <tr>
                <td>
                    <ul id="attr_list">
                        <?php echo ($attrText); ?>
                    </ul>
                </td>
            </tr>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 "/>
                    <input type="reset" class="button" value=" 重置 "/>
                </td>
            </tr>
        </table>
        <table cellspacing="1" cellpadding="3" width="100%" class="tab" style="display: none">
            <tr>
                <td class="label">
                    原图：
                </td>
                <td>
                    <input type="file" name="logo"/><br/>
                    <?php showImage($data['logo'], 100); ?>                                                    </td>
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
<link href="/Public/Umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="/Public/Umeditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Umeditor/umeditor.min.js"></script>
<script type="text/javascript" src="/Public/Umeditor/lang/zh-cn/zh-cn.js"></script>
<script type="application/javascript">
    UM.getEditor('goods_desc', {
        initialFrameWidth: "100%",
        initialFrameHeight: 350
    })

    $('#tabbar-div p span').click(function () {
        let i = $(this).index();
        $(this).removeClass('tab-back').addClass('tab-front').siblings().removeClass('tab-front').addClass('tab-back');
        $('.tab').eq(i).show().siblings().hide();
    })

    $("select[name='type_id']").change(function () {
        // 获取当前选中的类型id
        let typeId = $(this).val();
        let li = "";

        if (typeId > 0) {
            if (typeId == "<?php echo ($data["type_id"]); ?>") {
                li += '<?php echo $attrText ?>';
            } else {
                // 根据类型id执行ajax取出这个类型下的属性，冰获取返回的json数量
                $.ajax({
                    type: "get",
                    url: "<?php echo U('getAttribute', '', false); ?>/type_id/" + typeId,
                    dataType: "json",
                    async: false,
                    success: function (data) {
                        $(data).each(function (k, v) {
                            li += '<li>';
                            if (v.attr_type == '可选') {
                                li += '<a onclick="addNewAttr(this)">[+]</a>'
                            }
                            li += v.attr_name + '：';
                            if (v.attr_values == "") {
                                li += '<input type="text" name="attr_value[' + v.id + ']" />';
                            } else {
                                li += '<select name="attr_value[' + v.id + '][]"><option value="">请选择</option>';
                                // 把可选值根据逗号转换成数组
                                let _attr = v.attr_values.split('，');
                                for (let i = 0; i < _attr.length; ++i) {
                                    li += '<option value="' + _attr[i] + '">' + _attr[i] + '</option>'
                                }
                                li += '</select>';
                            }
                            li += '</li>';
                        });
                    }
                });
            }
        }

        $('#attr_list').html(li);
    });

    function addNewAttr(obj) {
        let li = $(obj).parent();

        if ($(obj).text() == '[+]') {
            let newLi = li.clone();
            newLi.find('a').text('[-]')
            newLi.find('option:selected').removeAttr('selected');
            newLi.find("input[name='goods_attr_id[]']").val("");
            li.after(newLi);
        } else {
            // 先获取这个属性值的id
            let id = li.find("input[name='goods_attr_id[]']").val();
            if (id != '') {
                if (confirm('确定要删除吗？')) {
                    $.ajax({
                        type: "get",
                        url: "<?php echo U('delAttribute', '', false) ?>/id/" + id + "/goods_id/<?php echo ($data['id']); ?>",
                        async: false,
                        success: function (data) {
                            if (data.code == false) {
                                return false;
                            }
                        }
                    });
                }
            }

            li.remove();
        }
    }
</script>

<div id="footer"> www.or.com</div>
</body>
</html>
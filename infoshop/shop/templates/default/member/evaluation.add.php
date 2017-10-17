<?php defined('CorShop') or exit('Access Invalid!'); ?>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/fileupload/jquery.iframe-transport.js"
        charset="utf-8"></script>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/fileupload/jquery.ui.widget.js"
        charset="utf-8"></script>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/fileupload/jquery.fileupload.js"
        charset="utf-8"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.raty').raty({
            path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
            click: function (score) {
                $(this).find('[nctype="score"]').val(score);
            }
        });

        $('.raty-x2').raty({
            path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
            starOff: 'star-off-x2.png',
            starOn: 'star-on-x2.png',
            width: 150,
            click: function (score) {
                $(this).find('[nctype="score"]').val(score);
            }
        });
        //图片上传
        $(".input-file").fileupload({
            dataType: 'json',
            url: "<?php echo urlShop('sns_album', 'swfupload');?>",
            formData: "",
            add: function (e, data) {
                data.formData = {category_id:<?php echo $output['ac_id'];?>}
                data.submit();
            },
            done: function (e, data) {
                if (data.result.state == "true") {
                    $item = $(this).parents('li');
                    $item.find('img').attr('src', data.result.file_url);
                    var file = $item.find('[nctype="input_image"]').val() + data.result.file_name;
                    $item.find('[nctype="input_image"]').val(file);
                    var file_id = $item.find('[nctype="del"]').attr('data-file-id');
                    album_pic_del(file_id);
                    $item.find('[nctype="del"]').attr('data-file-id', data.result.file_id);
                    $item.find('[nctype="image_item"]').show();
                } else {
                    showError("已经超出允许上传图片数量，请先删除相册图片再上传！");
                }
            }
        });

        $('[nctype="del"]').on('click', function () {
            album_pic_del($(this).attr('data-file-id'));
            $item = $(this).parent();
            $item.find('[nctype="input_image"]').val('');
            $item.hide();
        });

        function album_pic_del(file_id) {
            var del_url = "<?php echo urlShop('sns_album', 'album_pic_del');?>";
            del_url += '&id=' + file_id;
            $.get(del_url);
        }

        $('#btn_submit').on('click', function () {
            ajaxpost('evalform', '', '', 'onerror')
        });


    });
</script>

<div class="wrap-shadow">
    <div class="wrap-all ncu-order-view">
        <h2><?php echo $lang['member_evaluation_toevaluategoods']; ?></h2>

        <form id="evalform" method="post"
              action="index.php?act=member_evaluate&op=add&order_id=<?php echo $_GET['order_id']; ?>">
            <h3 class="mt20 mb10">商品评价</h3>

            <div class="ncm-notes">
                <ul>
                    <li><?php echo $lang['member_evaluation_rule_1']; ?></li>
                    <li><?php echo $lang['member_evaluation_rule_3']; ?></li>
                    <li><?php echo $lang['member_evaluation_rule_4']; ?></li>
                </ul>
            </div>
            <table class="ncu-table-style order deliver">
                <tbody>
                <tr>
                    <th colspan="20"><span class="ml10"><?php echo $lang['member_evaluation_order_desc']; ?></span><span
                            class="fr mr20"> <input type="checkbox" name="anony" checked>
              &nbsp;<?php echo $lang['member_evaluation_modtoanonymous']; ?></span>
                    </th>
                </tr>
                <?php if (!empty($output['order_goods'])){ ?>
                <?php foreach ($output['order_goods'] as $goods){ ?>
                <tr>
                    <td class="bdl w10"></td>
                    <td class="w70">
                        <div class="goods-pic-small">
								<span class="thumb size60"><i></i><a
                                        href="index.php?act=goods&goods_id=<?php echo $goods['goods_id']; ?>"
                                        target="_blank"><img
                                            src="<?php echo $goods['goods_image_url']; ?>"
                                            onload="javascript:DrawImage(this,60,60);"/></a></span>
                        </div>
                    </td>
                    <td class="tl goods-info">
                        <dl>
                            <dt>
                                <a
                                    href="index.php?act=goods&goods_id=<?php echo $goods['goods_id']; ?>"
                                    target="_blank"><?php echo $goods['goods_name']; ?></a>
                            </dt>
                            <dd class="tr">
                                <span
                                    class="price"><?php echo $goods['goods_price']; ?></span>&nbsp;x&nbsp;<?php echo $goods['goods_num']; ?>
                            </dd>
                        </dl>
                    </td>
                    <td class="bdr">
                        <div class="ncgeval mb10">
                            <div
                                class="raty" <?php echo $output['order_info']['evaluation_state'] != 2 ? '' : 'style="display:none;" ' ?>>
                                <input nctype="score"
                                       name="goods[<?php echo $goods['goods_id']; ?>][score]"
                                       type="hidden">
                            </div>
                                <span style="font-size: 12px; padding: 10px;">

                                    <?php
                                    //20150820 tjz增加 显示历史评论
                                    foreach ($output['evaluate_goods'] as $evaluate_goods) {
                                        if ($evaluate_goods['geval_goodsid'] == $goods['goods_id']) {
                                            ?>
                                            <p>
                                                评分记录： <?php echo $evaluate_goods['geval_scores'] . "分"; ?>
                                            </p>
                                            <br/>
                                            <p>
                                                评价内容： <?php echo $evaluate_goods['geval_content']; ?>
                                            </p>
                                            <br/>
                                            <p>
                                            评价时间：<?php echo "[" . date('Y-m-d H:i:s', $evaluate_goods['geval_addtime']) . "]";
                                        } ?>
                                        </p>

                                    <?php } ?>
                                </span>
								<textarea
                                    name="goods[<?php echo $goods['goods_id']; ?>][comment]"
                                    cols="150" rows="5" class="w400" maxlength="250"></textarea>
                    </td>
                </tr>
                <!-- 20150909 sj 增加晒单评价功能-->
                <?php if ($output['order_info']['evaluation_state'] != -1) { ?>
                    <tr>
                        <th colspan="20"><span
                                class="ml10"><?php echo $lang['member_evaluation_order_image']; ?></span></span>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="100">
                            <div class="evaluation-image">
                                <ul>
                                    <?php for ($i = 0; $i < 5; $i++) { ?>
                                        <li>
                                            <div class="upload-thumb">
                                                <div nctype="image_item" style="display: none;">
                                                    <img src=""> <input type="hidden" nctype="input_image"
                                                                        name="evaluate_image[]"
                                                                        value="<?php echo $output['order_info']['order_id'] . '_' . $goods['goods_id'] . 'DPX'; ?>">
                                                    <a href="javascript:;"
                                                       nctype="del" class="del" title="移除">X</a>
                                                </div>
                                            </div>
                                            <div class="upload-btn">
                                                <a href="javascript:void(0);"> <span> <input type="file"
                                                                                             hidefocus="true" size="1"
                                                                                             class="input-file"
                                                                                             name="file">
							</span>

                                                    <p>图片上传</p>
                                                </a>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <dl class="help">
                                    <dt>图片上传要求：</dt>
                                    <dd>请使用jpg\jpeg\png等格式、单张大小不超过1M的图片，最多可发布5张晒图，上传后的图片也将被保存在个人主页相册中以便其它使用。</dd>
                                </dl>
                            </div>
                        </th>
                    </tr>
                <?php } ?>


                </tbody>
                <?php } ?>
                <?php } ?>
                <tfoot>
                <tr>
                    <td colspan="20"></td>
                </tr>
                </tfoot>
            </table>


            <h3 <?php
            //20150820 tjz修改 追加评论时候不显示以下数据
            echo $output['order_info']['evaluation_state'] != 2 ? '' : 'style="display:none;" ' ?>>店铺信息及服务评价</h3>

            <div
                class="ncu-evaluation-store" <?php echo $output['order_info']['evaluation_state'] != 2 ? '' : 'style="display:none;" ' ?>>
                <div class="ncs-info">
                    <div class="title">
                        <h4><?php echo $output['store_info']['store_name']; ?></h4>
                    </div>
                    <div class="content">
                        <dl class="all-rate">
                            <dt>综合评分：</dt>
                            <dd>
                                <div class="rating">
                                    <span
                                        style="width: <?php echo $output['store_info']['store_credit_percent']; ?>%"></span>
                                </div>
                                <em><?php echo $output['store_info']['store_credit_average']; ?></em>分
                            </dd>
                        </dl>
                        <div class="detail-rate">
                            <h5>
                                <strong><?php echo $lang['member_evaluation_storeevalstat']; ?></strong>与行业相比
                            </h5>
                            <ul>
                                <?php foreach ($output['store_info']['store_credit'] as $value) { ?>
                                    <li> <?php echo $value['text']; ?><span
                                            class="credit"><?php echo $value['credit']; ?> 分</span>
                                        <span
                                            class="<?php echo $value['percent_class']; ?>"><i></i><?php echo $value['percent_text']; ?>
                                            <em><?php echo $value['percent']; ?></em></span>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php if (!empty($output['store_info']['store_qq']) || !empty($output['store_info']['store_ww'])) { ?>
                            <dl class="messenger">
                                <dt>联系方式：</dt>
                                <dd member_id="<?php echo $output['store_info']['member_id']; ?>">
                                    <?php if (!empty($output['store_info']['store_qq'])) { ?>
                                        <a target="_blank"
                                           href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq']; ?>&site=qq&menu=yes"
                                           title="QQ: <?php echo $output['store_info']['store_qq']; ?>"><img
                                                border="0"
                                                src="http://wpa.qq.com/pa?p=2:<?php echo $output['store_info']['store_qq']; ?>:52"
                                                style="vertical-align: middle;"/></a>
                                    <?php } ?>
                                    <?php if (!empty($output['store_info']['store_ww'])) { ?>
                                        <a target="_blank"
                                           href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $output['store_info']['store_ww']; ?>&site=cntaobao&s=1&charset=<?php echo CHARSET; ?>"><img
                                                border="0"
                                                src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $output['store_info']['store_ww']; ?>&site=cntaobao&s=2&charset=<?php echo CHARSET; ?>"
                                                alt="<?php echo $lang['nc_message_me']; ?>"
                                                style="vertical-align: middle;"/></a>
                                    <?php } ?>
                                </dd>
                            </dl>
                        <?php } ?>
                        <dl class="no-border">
                            <dt>公司名称：</dt>
                            <dd><?php echo $output['store_info']['store_company_name']; ?></dd>
                        </dl>
                    </div>
                </div>
                <div class="ncu-form-style">
                    <h4>我对该店此次服务的评分</h4>
                    <dl>
                        <dt><?php echo $lang['member_evaluation_evalstore_type_1'] . $lang['nc_colon']; ?></dt>
                        <dd style="width: 450px;">
                            <div class="raty-x2">
                                <input nctype="score" name="store_desccredit" type="hidden">
                            </div>
                        </dd>
                    </dl>
                    <dl>
                        <dt><?php echo $lang['member_evaluation_evalstore_type_2'] . $lang['nc_colon']; ?></dt>
                        <dd style="width: 450px;">
                            <div class="raty-x2">
                                <input nctype="score" name="store_servicecredit" type="hidden">
                            </div>
                        </dd>
                    </dl>
                    <dl>
                        <dt><?php echo $lang['member_evaluation_evalstore_type_3'] . $lang['nc_colon']; ?></dt>
                        <dd style="width: 450px;">
                            <div class="raty-x2">
                                <input nctype="score" name="store_deliverycredit" type="hidden">
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="clear"></div>
            <div class="mt30 tc">
                <input id="btn_submit" type="button" class="submit"
                       value="<?php echo $lang['member_evaluation_submit']; ?>"/>
            </div>
        </form>


<?php
/* @var $this yii\web\View */

$this->title = '消息';

?>

<div>
    <script>
        var strPage = 'message';
        $("#id_sidebar li.active").removeClass("active");
        $("#id_navbar li.c_header_active").removeClass("c_header_active");
        var strDomId = "id_" + strPage + "_page";
        $("#" + strDomId + "_s").addClass("active");
        $("#" + strDomId + "_h").addClass("c_header_active");
    </script>
    <div class="">
        <h3>消息列表</h3>
        <br />
        <div class="table-responsive">
            <table id="id_user_list" class="table table-striped table-hover">
                <tr>
                    <td>内容</td>
                    <td>时间</td>
                </tr>
                <?php foreach ($arrMessage as $item):?>
                    <tr class="active">
                        <td><?= $item['content'];?></td>
                        <td><?= $item['create_time_format'];?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div id="page_list">
                <?php if (!empty($objPage)) { echo yii\widgets\LinkPager::widget(['pagination' => $objPage,]);} ?>
            </div>
        </div>

    </div>
</div>
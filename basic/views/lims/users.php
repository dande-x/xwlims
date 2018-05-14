<?php
/* @var $this yii\web\View */

$this->title = '用户列表';
?>

<div>
    <script>
        var strPage = 'users';
        $("#id_sidebar li.active").removeClass("active");
        $("#id_navbar li.c_header_active").removeClass("c_header_active");
        var strDomId = "id_" + strPage + "_page";
        $("#" + strDomId + "_s").addClass("active");
        $("#" + strDomId + "_h").addClass("c_header_active");
    </script>
    <div class="">
        <h3>用户列表</h3>
        <form class="form-inline">
            <div class="form-group">
                <input type="text" class="form-control" id="id_search_user_name" placeholder="姓名">
            </div>
            <a class="btn btn-default" onclick="searchUser()">搜索</a>
        </form>
        <br />
        <div class="table-responsive">
            <table id="id_user_list" class="table table-striped table-hover">
                <tr>
                    <td>姓名</td>
                    <td>人员类型</td>
                    <td>专业班级</td>
                    <td>课题组</td>
                    <td>组织机构</td>
                    <td>电子邮箱</td>
                    <td>联系电话</td>
                    <td>地址</td>
                </tr>
                <?php foreach ($arrUsers as $item):?>
                <tr class="active">
                    <td><?= $item['user_name'];?></td>
                    <td><?= $item['user_type_format'];?></td>
                    <td><?= $item['user_class'];?></td>
                    <td><?= $item['group_name'];?></td>
                    <td><?= $item['organization'];?></td>
                    <td><?= $item['email'];?></td>
                    <td><?= $item['phone'];?></td>
                    <td><?= $item['address'];?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div id="page_list">
            <?= yii\widgets\LinkPager::widget(['pagination' => $objPage]) ?>
        </div>
    </div>

    <script>
        function searchUser() {
            var strSearchName = document.getElementById('id_search_user_name').value;
            if ('' == strSearchName){
                window.location.href="index.php?r=lims/users";
                return false;
            }
            window.location.href="index.php?r=lims/users&search_user_name=" + strSearchName;
        }
        document.getElementById('id_search_user_name').value = getUrlParam('search_user_name');

    </script>
</div>
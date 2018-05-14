<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

// use yii\bootstrap\Nav;
// use yii\bootstrap\NavBar;
// use yii\widgets\Breadcrumbs;
// use app\widgets\Alert;
use yii\bootstrap\Alert;
use app\assets\AppAsset;

AppAsset::register($this);

$bolIsLogin = $this->params['isLogin'];
$strUserName =  isset($this->params['login_user_info']['user_name']) ? $this->params['login_user_info']['user_name'] : '游客';
$strUserType =  isset($this->params['login_user_info']['user_type']) ? $this->params['login_user_info']['user_type'] : '学生';
$strGroupName =  isset($this->params['login_user_info']['group_name']) ? $this->params['login_user_info']['group_name'] : '默认';
$intGroupId =  isset($this->params['login_user_info']['group_id']) ? $this->params['login_user_info']['group_id'] : 0;
$strOrganization =  isset($this->params['login_user_info']['organization_name']) ? $this->params['login_user_info']['organization_name'] : '默认';
$intOrganizationId =  isset($this->params['login_user_info']['organization_id']) ? $this->params['login_user_info']['organization_id'] : 0;
$strUserClass =  isset($this->params['login_user_info']['user_class']) ? $this->params['login_user_info']['user_class'] : '默认';
$strUserEmail =  isset($this->params['login_user_info']['email']) ? $this->params['login_user_info']['email'] : '';
$strUserPhone =  isset($this->params['login_user_info']['phone']) ? $this->params['login_user_info']['phone'] : 0;
$strUserAddress=  isset($this->params['login_user_info']['address']) ? $this->params['login_user_info']['address'] : '';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <meta name="author" content="xwhdlm">

    <?= Html::cssFile('@web/assets/css/bootstrap.min.css') ?>
    <?= Html::cssFile('@web/assets/css/header.css') ?>
    <?= Html::cssFile('@web/assets/css/common.css') ?>
    <?= Html::cssFile('@web/assets/css/sidebar.css') ?>
    <link rel="icon" href="favicon.ico">

    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>

<?php
if ($bolIsLogin) {
    echo '<style  type="text/css">
.c_header_notlogin{
    display: none !important;
}
</style>';
} else {
    echo '<style  type="text/css">
.c_header_login{
    display: none !important;
}
</style>';
    echo '<script>
window.location.href="index.php?r=lims/login"; 
</script>';
}


/*if( Yii::$app->getSession()->hasFlash('info') ) {
    //echo Yii::$app->getSession()->getFlash('info');
    Alert::widget([
        'options' => [
            'class' => 'alert-success c_alert_margin', //这里是提示框的class
        ],
        'body' => Yii::$app->getSession()->getFlash('info'), //消息体
    ]);
}*/


?>
<div id="id_alert" onclick="hiddenAlert()" class="c_alert alert-success <?php if(!Yii::$app->getSession()->hasFlash('info')) {echo 'hidden';}?>">
    <?=Yii::$app->getSession()->getFlash('info')?>
</div>
<script>
    function hiddenAlert() {
        document.getElementById('id_alert').className = 'hidden';
    }
</script>
<header id="id_header">
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" onclick="changePage(0)">XW-LIMS</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <div class="navbar-right">

                    <ul class="nav navbar-nav">
                        <li class="c_navbar_form">
                            <form class="navbar-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <div class="c_header_search_icon">
                                    <span class="glyphicon glyphicon-search"></span>
                                </div>
                            </form>
                        </li>
                        <li class="c_header_login">
                            <a class="c_header_message" href="#" onclick="changePage(7)">
                                <span class="glyphicon glyphicon-envelope"></span>
                            </a>
                        </li>
                        <li class="dropdown c_header_login">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <u>
                                    <span id="login_user"
                                          class="c_header_user_name"><?php echo $strUserName; ?></span>
                                </u>
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class=" page dropdown-li">
                                    <a href="<?php echo yii\helpers\Url::to(['lims/homepage']) ?>">个人信息</a>
                                </li>
                                <li class=" page dropdown-li">
                                    <a href="<?php echo yii\helpers\Url::to(['lims/logout']) ?>">退出登录</a>
                                </li>
                            </ul>
                        </li>
                        <li class="c_header_notlogin">
                            <a class="c_header_message c_header_notlogin" href="<?php echo yii\helpers\Url::to(['lims/login']) ?>">登录</a>
                        </li>
                        <li class="c_header_notlogin">
                            <a class="c_header_message c_header_notlogin" href="<?php echo yii\helpers\Url::to(['lims/register']) ?>"> 注册</a>
                        </li>
                    </ul>
                    <ul id="id_navbar" class="nav navbar-nav c_header_sidebar">
                        <li id="id_homepage_page_h" onclick="changePage(0)">
                            <a href="#">首页 <span class="sr-only">(current)</span></a>
                        </li>
                        <li id="id_group_page_h" onclick="changePage(1)">
                            <a href="#">课题组</a>
                        </li>
                        <li id="id_users_page_h" onclick="changePage(2)">
                            <a href="#">用户列表</a>
                        </li>
                        <li id="id_instrument_page_h" onclick="changePage(3)">
                            <a href="#">仪器列表</a>
                        </li>
                        <li id="id_appointment_page_h" onclick="changePage(4)">
                            <a href="#">预约管理</a>
                        </li>
                        <li id="id_statistics_page_h" onclick="changePage(5)">
                            <a href="#">仪器统计</a>
                        </li>
                       <!-- <li id="id_reim_page_h" onclick="changePage(6)">
                            <a href="#">转账报销</a>
                        </li>-->
                        <li id="id_message_page_h" onclick="changePage(7)">
                            <a href="#">消息中心</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
<div class="container-fluid">
    <div class="row">
        <div id="id_sidebar" class="col-sm-2 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li id="id_homepage_page_s" onclick="changePage(0)">
                    <a href="#">首页 <span class="sr-only">(current)</span></a>
                </li>
                <li id="id_group_page_s" onclick="changePage(1)">
                    <a href="#">课题组</a>
                </li>
                <li id="id_users_page_s" onclick="changePage(2)">
                    <a href="#">用户列表</a>
                </li>
            </ul>
            <ul class="nav nav-sidebar">
                <li id="id_instrument_page_s" onclick="changePage(3)">
                    <a href="#">仪器列表</a>
                </li>
                <li id="id_appointment_page_s" onclick="changePage(4)">
                    <a href="#">预约管理</a>
                </li>
                <li id="id_statistics_page_s" onclick="changePage(5)">
                    <a href="#">仪器统计</a>
                </li>
            </ul>
            <ul class="nav nav-sidebar">
                <!--<li id="id_reim_page_s" onclick="changePage(6)">
                    <a href="#">转账报销</a>
                </li>-->
                <li id="id_message_page_s" onclick="changePage(7)">
                    <a href="#">消息中心</a>
                </li>
            </ul>
        </div>

    </div>
</div>

<?= Html::jsFile('@web/assets/js/lib.js') ?>
<?= Html::jsFile('@web/assets/js/jquery-3.3.1.min.js') ?>
<?= Html::jsFile('@web/assets/js/bootstrap.min.js') ?>
<?= Html::jsFile('@web/assets/js/holder.min.js') ?>
<?= Html::jsFile('@web/assets/js/vue.min.js') ?>

<div class="col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2 main">
    <div id="id_page_container" class="container-fluid">
        <?= $content ?>
    </div>

</div>

<footer id="id_footer">

</footer>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script>
    function changePage(intPageNum) {
        var arrPage = [
            "homepage",
            "group",
            "users",
            "instrument",
            "appointment",
            "statistics",
            "reim",
            "message"
        ];
        var strPage = arrPage[intPageNum];
        $("#id_page_container").load(strPage + ".html");
        window.location.href = "index.php?r=lims/" + strPage;

        /*$("#id_sidebar li.active").removeClass("active");
         $("#id_navbar li.c_header_active").removeClass("c_header_active");
         var strDomId = "id_" + strPage + "_page";
         $("#" + strDomId + "_s").addClass("active");
         $("#" + strDomId + "_h").addClass("c_header_active");*/
    }

    $(function () {
        // $("#id_footer").load("footer.html");
        document.body.addEventListener('touchstart', function () {
        });
        /* var intCurrentPage = getCookie("current_page");
         if("" == intCurrentPage) {
         intCurrentPage = 0;
         }
         changePage(intCurrentPage);*/
    })
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

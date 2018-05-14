<?php
/**
 * Created by PhpStorm.
 * User: xwhdlm
 * Date: 2018/4/28
 * Time: 11:34
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'lims-重置密码';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>xwlims-重置密码</title>
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="assets/css/footer.css" />
    <link rel="stylesheet" href="assets/css/login.css" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .c_notice{
            text-align: center;
        }
        .c_login{
            text-align: center;
        }
        .c_padding_left{
            padding-left: 100px;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="c_login_logo_container">
        <img class="c_login_logo" src="assets/img/logo.gif" />
    </div>
    <!--<form class="form-signin">-->
    <form method="post" class="form-signin <?php if (!$bolCheck){echo 'hidden';} ?>" action="<?= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'] ?>">
        <h3 class="form-signin-heading"></h3>
        <label for="id_input_email" class="sr-only">邮箱</label>
        <input type="text" id="id_input_email" class="form-control" placeholder="&nbsp邮箱" required value="<?=$strEmail?>" readonly>
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <br>
        <label for="id_input_password" class="sr-only">密码</label>
        <input type="password" id="id_input_password" name="password" class="form-control" placeholder="&nbsp密码" required autofocus>

        <label for="id_input_password_r" class="sr-only">重复密码</label>
        <input type="password" id="id_input_password_r" name="password_r" class="form-control" placeholder="&nbsp重复密码" required autofocus>
        <?= Html::submitButton('重置密码',['class'=>'btn btn-lg btn-primary btn-block'])?>
    </form>
    <p class="c_notice"><?=$strNotice?></p>
    <!-- </form>-->
    <div class="c_login"><a class="c_padding_left" href="index.php?r=lims/login">&emsp;&emsp;去登录&emsp;&emsp;</a></div>
</div>
<!-- /container -->

<footer id="id_footer">
</footer>
<script type="text/javascript" src="assets/js/jquery-3.3.1.min.js"></script>

<script type="text/javascript">
    $(function() {
        $("#id_footer").load("lims/footer.html");
    })
</script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/holder.min.js"></script>
<script type="text/javascript" src="assets/js/lib.js"></script>
<script type="text/javascript" src="assets/js/vue.min.js"></script>
</body>

</html>
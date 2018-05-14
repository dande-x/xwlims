<?php
/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'lims-登录';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>xwlims-登录</title>
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="assets/css/footer.css" />
    <link rel="stylesheet" href="assets/css/login.css" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="c_login_logo_container">
        <img class="c_login_logo" src="assets/img/logo.gif" />
    </div>
    <!--<form class="form-signin">-->
    <?php $form = ActiveForm::begin([
        'options'=> ['class'=>'form-signin'],
        'fieldConfig' => [
            'template' => '{input}{error}',
        ],
    ]); ?>
    <h3 class="form-signin-heading"></h3>
    <label for="email" class="sr-only">邮箱</label>
    <!--<input type="text" id="email" class="form-control" placeholder="&nbsp用户名" required autofocus>-->
    <?php echo $form->field($model,'email')->textInput(['id'=>'email','class'=>'form-control','placeholder'=>'邮箱']) ?>
    <label for="password" class="sr-only">密码</label>
    <!--<input type="password" id="inputPassword" class="form-control" placeholder="&nbsp密码" required>-->
    <?php echo $form->field($model,'password')->textInput(['id'=>'password','type'=>'password','class'=>'form-control','placeholder'=>'密码']) ?>

    <div class="checkbox">
        <!-- <input type="checkbox" value="remember-me">记住我-->
        <?php echo $form->field($model,'remember')->checkbox(['id' => 'id_remember',]); ?>
    </div>
    <!--<button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>-->
    <?php  echo Html::submitButton('登录',['class'=>'btn btn-lg btn-primary btn-block'])?>
    <div class="c_login_bottom">
        <div class="c_login_toregister_container">
            <a class="c_login_toregister" href="index.php?r=lims/register">没有账号?去注册</a>
        </div>
        <div class="c_login_forgot_container">
            <a class="c_login_forgot" href="index.php?r=lims/forgetpassword">忘记密码</a>
        </div>
    </div>
    <?php $form = ActiveForm::end(); ?>

    <!-- </form>-->
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
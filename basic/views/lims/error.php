<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

// $this->title = $name;
$this->title = '发生异常';
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        oops,请求发生了一些错误，请重试。
    </p>
    <p>
        如果错误持续发生，请联系管理员，谢谢。
    </p>

</div>

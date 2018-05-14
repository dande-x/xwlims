<?php
/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


$this->title = 'lims-注册';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>xwlims-注册</title>
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="assets/css/footer.css" />
    <link rel="stylesheet" href="assets/css/register.css" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="c_register_logo_container">
        <img class="c_register_logo" src="assets/img/logo.gif" />
    </div>
    <?php $form = ActiveForm::begin([
        'options'=> ['class'=>'form-horizontal c_form_register'],
        'fieldConfig' => [
            'template' => '{input}{error}',
        ],
        'enableAjaxValidation'=>true,
    ]); ?>
        <div class="c_register_title_container">
            <p class="c_register_title">注册</p>
        </div>
        <div class="c_register_userinfo">
            <div class="form-group">
                <label for="id_input_email" class="col-sm-3 control-label">邮箱&emsp;&emsp;</label>
                <div class="col-sm-8 col-xs-11">
                   <!-- <input id="id_input_email" class="form-control" type="text" required="true" placeholder="&nbsp邮箱" />-->
                    <?php echo $form->field($objUserModel,'email',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_email','class'=>'form-control','required'=>'true','placeholder'=>'邮箱']); ?>

                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                <!--<div>
                    <span id="id_username_error_notice" class="col-xs-12 col-sm-7 col-sm-offset-3 help-block">邮箱已被使用!</span>
                </div>-->
            </div>
            <div class="form-group">
                <label for="id_input_passwd" class="col-sm-3 control-label">密码&emsp;&emsp;</label>
                <div class="col-sm-8 col-xs-11">
                    <!--<input id="id_input_passwd" class="form-control" required="true" type="password" placeholder="&nbsp密码" />-->
                    <?= $form->field($objUserModel,'password',['options'=>['tag'=>false]])->textInput(['id'=>'password','type'=>'password','required'=>'true','class'=>'form-control','placeholder'=>'密码']) ?>
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                <!--<div>
                    <span id="id_passwd_error_notice" class="col-xs-12 col-sm-7 col-sm-offset-3 help-block">密码不符合要求!</span>
                </div>-->
            </div>
            <div class="form-group">
                <label for="id_input_passwd_r" class="col-sm-3 control-label">重复密码</label>
                <div class="col-sm-8 col-xs-11">
                    <!--<input id="id_input_passwd_r" class="form-control" required="true" type="password" placeholder="&nbsp重复密码" />-->
                    <?= $form->field($objUserModel,'password_repeat',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_passwd_r','type'=>'password','required'=>'true','class'=>'form-control','placeholder'=>'重复密码']) ?>
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                <!--<div>
                    <span id="id_passwd_r_error_notice" class="col-xs-12 col-sm-7 col-sm-offset-3 help-block">密码不一致!</span>
                </div>-->
            </div>
        </div>
        <hr class="c_register_hr" />

        <div class="form-group">
            <div class="form-group-sm col-xs-12">
                <label for="id_input_name" class="col-sm-1 col-xs-12 control-label c_control_label_small">姓名</label>
                <div class="col-sm-4 col-xs-11">
                    <!--<input id="id_input_name" class="form-control" required="true" type="text" placeholder="姓名" />-->
                    <?= $form->field($objUserModel,'user_name',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_name','type'=>'text','required'=>'true','class'=>'form-control','placeholder'=>'姓名']) ?>

                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                <label for="id_input_user_code" class="col-sm-1 col-xs-12 control-label c_control_label_small">学号</label>
                <div class="col-sm-4 col-xs-11">
                    <!--<input id="id_input_user_code" class="form-control" required="true" type="text" placeholder="学号" />-->
                    <?= $form->field($objUserModel,'user_code',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_user_code','type'=>'text','required'=>'true','class'=>'form-control','placeholder'=>'学号']) ?>
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
            </div>

            <div class="form-group-sm col-xs-12">
                <label for="id_select_user_type" class="col-sm-1 col-xs-12 control-label c_control_label_small">人员类型</label>
                <div class="col-sm-4 col-xs-11">
                   <!-- <select id="id_select_user_type" name="User[user_type]" required="true" class="form-control">
                        <option disabled="disabled" value="0">学生</option>
                        <option value="1">&emsp;本科生</option>
                        <option value="2">&emsp;硕士研究生</option>
                        <option value="3">&emsp;博士研究生</option>
                        <option value="4">&emsp;博士后</option>
                        <option value="5">&emsp;其他</option>
                        <option disabled="disabled" value="0">教师</option>
                        <option value="10">&emsp;课题组负责人</option>
                        <option value="11">&emsp;科研助理</option>
                        <option value="12">&emsp;实验室管理员</option>
                        <option value="13">&emsp;其他</option>
                        <option disabled="disabled" value="0">其他</option>
                        <option value="21">&emsp;技术员</option>
                        <option value="22">&emsp;其他</option>
                    </select>-->

                    <?= $form->field($objUserModel, 'user_type',['options'=>['tag'=>false]])->dropDownList(['0' => '学生',
                        '1' => '　本科生',
                        '2' => '　硕士研究生',
                        '3' => '　博士研究生',
                        '4' => '　博士后',
                        '5' => '　其他',
                        '00' => '教师',
                        '10' => '　课题组负责人',
                        '11' => '　科研助理',
                        '12' => '　实验室管理员',
                        '13' => '　其他',
                        '000' => '其他',
                        '20' => '　技术员',
                        '21' => '　其他',
                    ], ['options' => ['0' => ['disabled' => true],
                        '00' => ['disabled' => true],
                        '0000' => ['disabled' => true],
                    ]])->hint('') ?>
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                <label for="id_input_class" class="col-sm-1 col-xs-12 control-label c_control_label_small">专业班级</label>
                <div class="col-sm-4 col-xs-11">
                    <!--<input id="id_input_class" class="form-control" type="text" placeholder="专业班级" />-->
                    <?= $form->field($objUserModel,'user_class',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_class','type'=>'text','class'=>'form-control','placeholder'=>'专业班级']) ?>
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require"></span>
            </div>

            <div class="form-group-sm col-xs-12">
                <label for="id_input_organization" class="col-sm-1 col-xs-12 control-label c_control_label_small">组织机构</label>
                <div class="col-sm-10 col-xs-11">
                    <!--<input id="id_input_organization" class="form-control" type="text" placeholder="姓名" />-->
                    <select id="id_input_organization" class="form-control" required="true" name = "User[organization_id]">
                        <?php foreach ($arrOrganizationMap as $intKey => $strItem): ?>
                            <!--<option disabled="disabled" value="1">学生</option>
                            <option value="22">&emsp;其他</option>-->
                            <option value="<?= $intKey?>" ><?php
                                $arrLevel1 = array();
                                if (isset($arrOrganizationStructure[$strItem])){
                                    echo $strItem;
                                } else{
                                    foreach ($arrOrganizationStructure as $key => $arr){
                                        foreach ($arr as $strName){
                                            if ($strName == $strItem){
                                                echo $key . '---' . $strItem;
                                                break 2;
                                            }
                                        }
                                    }
                                }
                                ?></option>
                        <?php  endforeach; ?>
                    </select>
                   </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
            </div>

            <div class="form-group-sm col-xs-12">
                <label for="id_input_group" class="col-sm-1 col-xs-12 control-label c_control_label_small">课题组</label>
                <div class="col-sm-10 col-xs-11">
                    <select id="id_input_group" class="form-control" required="true" name = "User[group_id]">
                        <option>请选择...</option>
                        <?php foreach ($arrGroups as $item): ?>
                        <!--<option disabled="disabled" value="1">学生</option>
                        <option value="22">&emsp;其他</option>-->
                        <option value="<?= $item['group_id']?>" ><?= $item['group_name'] .' --- ' . $item['admin_user_name'].'老师'?></option>
                        <?php  endforeach; ?>
                    </select>
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require"></span>
            </div>
            <div class="form-group-sm col-xs-12">
                <label for="id_input_phone" class="col-sm-1 col-xs-12 control-label c_control_label_small">联系电话</label>
                <div class="col-sm-4 col-xs-11">
                    <!--<input id="id_input_phone" class="form-control" type="number" placeholder="联系电话" />-->
                    <?= $form->field($objUserModel,'phone',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_phone','type'=>'text','class'=>'form-control','placeholder'=>'联系电话']) ?>

                </div>
                <span class="col-sm-1 col-xs-1 c_input_require"></span>
                <label for="id_input_address" class="col-sm-1 col-xs-12 control-label c_control_label_small">地址</label>
                <div class="col-sm-4 col-xs-11">
                    <!--<input id="id_input_address" class="form-control" type="text" placeholder="地址" />-->
                    <?= $form->field($objUserModel,'address',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_address','type'=>'text','class'=>'form-control','placeholder'=>'地址']) ?>

                </div>
                <span class="col-sm-1 col-xs-1 c_input_require"></span>
            </div>
        </div>

        <div class="c_register_btn_container row">
            <!--<button class="col-sm-offset-2 col-sm-8 btn btn-lg btn-primary c_btn_register">注册</button>-->
            <?= Html::submitButton('注册',['class'=>'col-sm-offset-2 col-sm-8 btn btn-lg btn-primary c_btn_register'])?>

        </div>
        <div class="c_register_tologin_container">
            <a class="c_register_text c_register_tologin" href="index.php?r=lims/login">已有账号?去登录</a>
        </div>
    <?php $form = ActiveForm::end(); ?>


 <!--   <form class="form-horizontal c_form_rigister">
        <div class="c_register_title_container">
            <p class="c_register_title">注册</p>
        </div>
        <div class="c_register_userinfo">
            <div class="form-group">
                <label for="id_input_email" class="col-sm-3 control-label">邮箱&emsp;&emsp;</label>
                <div class="col-sm-8 col-xs-11">
                    <input id="id_input_email" class="form-control" type="text" required="true" placeholder="&nbsp邮箱" />
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                <div>
                    <span id="id_username_error_notice" class="col-xs-12 col-sm-7 col-sm-offset-3 help-block">邮箱已被使用!</span>
                </div>
            </div>
            <div class="form-group">
                <label for="id_input_passwd" class="col-sm-3 control-label">密码&emsp;&emsp;</label>
                <div class="col-sm-8 col-xs-11">
                    <input id="id_input_passwd" class="form-control" required="true" type="password" placeholder="&nbsp密码" />
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                <div>
                    <span id="id_passwd_error_notice" class="col-xs-12 col-sm-7 col-sm-offset-3 help-block">密码不符合要求!</span>
                </div>
            </div>
            <div class="form-group">
                <label for="id_input_passwd_r" class="col-sm-3 control-label">重复密码</label>
                <div class="col-sm-8 col-xs-11">
                    <input id="id_input_passwd_r" class="form-control" required="true" type="password" placeholder="&nbsp重复密码" />
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                <div>
                    <span id="id_passwd_r_error_notice" class="col-xs-12 col-sm-7 col-sm-offset-3 help-block">密码不一致!</span>
                </div>
            </div>
        </div>
        <hr class="c_register_hr" />

        <div class="form-group">
            <div class="form-group-sm col-xs-12">
                <label for="id_input_name" class="col-sm-1 col-xs-12 control-label c_control_label_small">姓名</label>
                <div class="col-sm-4 col-xs-11">
                    <input id="id_input_name" class="form-control" required="true" type="text" placeholder="姓名" />
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                <label for="id_input_studentID" class="col-sm-1 col-xs-12 control-label c_control_label_small">学号</label>
                <div class="col-sm-4 col-xs-11">
                    <input id="id_input_studentID" class="form-control" required="true" type="text" placeholder="学号" />
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
            </div>

            <div class="form-group-sm col-xs-12">
                <label for="id_select_user_type" class="col-sm-1 col-xs-12 control-label c_control_label_small">人员类型</label>
                <div class="col-sm-4 col-xs-11">
                    <select id="id_select_user_type" required="true" class="form-control">
                        <option disabled="disabled" value="1">学生</option>
                        <option value="1">&emsp;本科生</option>
                        <option value="2">&emsp;硕士研究生</option>
                        <option value="3">&emsp;博士研究生</option>
                        <option value="4">&emsp;博士后</option>
                        <option value="5">&emsp;其他</option>
                        <option disabled="disabled" value="10">教师</option>
                        <option value="10">&emsp;课题组负责人</option>
                        <option value="11">&emsp;科研助理</option>
                        <option value="12">&emsp;实验室管理员</option>
                        <option value="13">&emsp;其他</option>
                        <option disabled="disabled" value="21">其他</option>
                        <option value="21">&emsp;技术员</option>
                        <option value="22">&emsp;其他</option>
                    </select>
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                <label for="id_input_class" class="col-sm-1 col-xs-12 control-label c_control_label_small">专业班级</label>
                <div class="col-sm-4 col-xs-11">
                    <input id="id_input_class" class="form-control" type="text" placeholder="专业班级" />
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require"></span>
            </div>

            <div class="form-group-sm col-xs-12">
                <label for="id_input_organization" class="col-sm-1 col-xs-12 control-label c_control_label_small">组织机构</label>
                <div class="col-sm-10 col-xs-11">
                    <input id="id_input_organization" class="form-control" type="text" placeholder="姓名" />
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
            </div>

            <div class="form-group-sm col-xs-12">
                <label for="id_input_group" class="col-sm-1 col-xs-12 control-label c_control_label_small">课题组</label>
                <div class="col-sm-10 col-xs-11">
                    <select id="id_input_group" class="form-control" required="true">
                        <option disabled="disabled" value="1">学生</option>
                        <option value="22">&emsp;其他</option>
                    </select>
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require">*</span>
            </div>
            <div class="form-group-sm col-xs-12">
                <label for="id_input_phone" class="col-sm-1 col-xs-12 control-label c_control_label_small">联系电话</label>
                <div class="col-sm-4 col-xs-11">
                    <input id="id_input_phone" class="form-control" type="number" placeholder="联系电话" />
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require"></span>
                <label for="id_input_address" class="col-sm-1 col-xs-12 control-label c_control_label_small">地址</label>
                <div class="col-sm-4 col-xs-11">
                    <input id="id_input_address" class="form-control" type="text" placeholder="地址" />
                </div>
                <span class="col-sm-1 col-xs-1 c_input_require"></span>
            </div>
        </div>

        <div class="c_register_btn_container row">
            <button class="col-sm-offset-2 col-sm-8 btn btn-lg btn-primary c_btn_register">注册</button>
        </div>
        <div class="c_register_tologin_container">
            <a class="c_register_text c_register_tologin" href="index.php?r=lims/login">已有账号?去登录</a>
        </div>
    </form>-->







    <div>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
    </div>
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
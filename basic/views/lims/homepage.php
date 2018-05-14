<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

$this->title = '首页';

$strUserAvatar = '';

$bolIsLogin = $this->params['isLogin'];
$strUserName =  isset($this->params['login_user_info']['user_name']) ? $this->params['login_user_info']['user_name'] : '游客';
$strUserCode =  isset($this->params['login_user_info']['user_code']) ? $this->params['login_user_info']['user_code'] : 0;
$strUserType =  isset($this->params['login_user_info']['format_user_type']) ? $this->params['login_user_info']['format_user_type'] : '学生';
$intUserType =  isset($this->params['login_user_info']['user_type']) ? $this->params['login_user_info']['user_type'] : 0;
$strUserTypeFormat =  isset($this->params['login_user_info']['user_type_format']) ? $this->params['login_user_info']['user_type_format'] : '';

$strGroupName =  isset($this->params['login_user_info']['group_name']) ? $this->params['login_user_info']['group_name'] : '无';
$intGroupId =  isset($this->params['login_user_info']['group_id']) ? $this->params['login_user_info']['group_id'] : 0;
$strOrganization =  isset($this->params['login_user_info']['organization']) ? $this->params['login_user_info']['organization'] : '默认';
$intOrganizationId =  isset($this->params['login_user_info']['organization_id']) ? $this->params['login_user_info']['organization_id'] : 0;
$strUserClass =  isset($this->params['login_user_info']['user_class']) ? $this->params['login_user_info']['user_class'] : '默认';
$strUserEmail =  isset($this->params['login_user_info']['email']) ? $this->params['login_user_info']['email'] : '';
$strUserPhone =  isset($this->params['login_user_info']['phone']) ? $this->params['login_user_info']['phone'] : 0;
$strUserAddress=  isset($this->params['login_user_info']['address']) ? $this->params['login_user_info']['address'] : '';


?>

<div class="">
    <?=Html::cssFile('@web/assets/css/homepage.css')?>
    <?=Html::cssFile('@web/assets/css/register.css')?>
    <script>
        var strPage = 'homepage';
        $("#id_sidebar li.active").removeClass("active");
        $("#id_navbar li.c_header_active").removeClass("c_header_active");
        var strDomId = "id_" + strPage + "_page";
        $("#" + strDomId + "_s").addClass("active");
        $("#" + strDomId + "_h").addClass("c_header_active");
    </script>
    <div class="c_homepage_container">
        <div class="c_homepage_userinfo row">
            <div class="c_homepage_avatar_container col-md-3  col-sm-3 col-xs-8 col-xs-offset-2">
                <!--<img class="img-responsive c_homepage_avatar" src="../img/default.png" />-->
                <?= Html::img('@web/assets/img/default.png',['class' => 'img-responsive c_homepage_avatar'], ['alt' => 'Avatar']) ?>
            </div>
            <div class="c_homepage_infolist_container col-md-7 col-sm-7 col-xs-12">
                <ul class=" c_homepage_infolist list-group">
                    <li class="c_user_name list-group-item">姓名&emsp;&emsp;&nbsp;:&emsp;<strong><?php echo $strUserName;?></strong></li>
                    <li class="list-group-item">人员类型&nbsp;:&emsp;<strong><?php echo $strUserTypeFormat;?></strong></li>
                    <li class="list-group-item">学号\工号&nbsp;:&emsp;<strong><?php echo $strUserCode;?></strong></li>
                    <li class="list-group-item">课题组&emsp;&nbsp;:&emsp;<strong><?php echo $strGroupName;?></strong></li>
                    <li class="list-group-item">组织机构&nbsp;:&emsp;<strong><?php echo $strOrganization;?></strong></li>
                    <li class="list-group-item">专业班级&nbsp;:&emsp;<strong><?php echo $strUserClass;?></strong></li>
                    <li class="list-group-item">电子邮箱&nbsp;:&emsp;<strong><?php echo $strUserEmail;?></strong></li>
                    <li class="list-group-item">联系电话&nbsp;:&emsp;<strong><?php echo $strUserPhone;?></strong></li>
                    <li class="list-group-item">地址&emsp;&emsp;&nbsp;:&emsp;<strong><?php echo $strUserAddress;?></strong></li>
                </ul>
                <div class=" c_homepage_infobutton">
                    <button class="btn btn-default" data-toggle="modal" data-target="#id_modal_update">修改资料</button>
                </div>
            </div>
        </div>
        <hr />
        <div class="c_homepage_below_container">
            </br>
            <h3>关注仪器</h3>
            <div class="table-responsive">
                <table id="id_instrument_list" class="table table-striped table-hover">
                    <tr>
                        <td>仪器名称</td>
                        <td>放置地点</td>
                        <td>负责人</td>
                        <td>组织机构</td>
                        <td>操作</td>
                    </tr>
                    <?php foreach ($arrFollowInstrument as $item):?>
                        <tr class="active">
                            <td><?php echo $item['instrument_name'];?></td>
                            <td><?php echo $item['address'];?></td>
                            <td><?php echo $item['admin_user_name'];?></td>
                            <td><?php echo $item['organization'];?></td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="<?php $strBtbUrl = 'user/unfollow'; echo yii\helpers\Url::to([$strBtbUrl,'instrument_id'=>$item['instrument_id']]) ?>"><?php echo '取消关注';?></a>
                                <a type="button" class="btn btn-info btn-sm" href="<?php echo yii\helpers\Url::to(['lims/instrument','instrument_id' => $item['instrument_id']]) ?>">详情</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>


                    <!--<tr v-for="instrument in instruments" class="active">
                        <td>{{instrument.instrument_name}}</td>
                        <td>{{instrument.location}}</td>
                        <td>{{instrument.admin}}</td>
                        <td>{{instrument.appointment_status}}</td>
                        <td>{{instrument.appointment_time}}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" v-on:click="update_music">取消关注</button>
                        </td>
                    </tr>-->
                </table>
            </div>
            <div id="page_list">
                <?php echo yii\widgets\LinkPager::widget(['pagination' => $objFollowInstrumentPage]) ?>
                <!--<nav aria-label="Page navigation">
                    <ul class="pagination pagination-lg">
                        <li>
                            <a v-on:click="pagePrevious" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li v-for="page_number in pages" v-bind:class="{active : page_number.is_active}" v-on:click="changePage(page_number.page_index)">
                            <a v-bind:class="{hidden : page_number.is_hidden}">{{page_number.page_index}}</a>
                        </li>
                        <li>
                            <a v-on:click="pageNext" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>-->
            </div>
        </div>
    </div>
    <script type="text/javascript" src="assets/js/page.js" ></script>
    <script>
        var strUrl = '/actions/instrument/getFollowInstrumentList.php';
        var instrumentList = new Vue({
            el: '#instrument_list',
            data: {
                instrument_count: 0,
                instruments: []
            },
            methods: {
                unfollow: function(event) {
                    var strInstrumentName = 1;
                    var intInstrumentId = 1;
                    var bolUnfollow = confirm("确定取消关注" + strInstrumentName + "?");
                    var arrInstrumentId = {};
                    arrInstrumentId.instrument_id = intInstrumentId;
                    var strData = JSON.stringify(arrInstrumentId);
                    console.log(strData);
                    if(bolUnfollow) {
                        $.ajax({
                            type: 'POST',
                            contentType: "application/json;charset=utf-8",
                            url: "/actions/instrument/unfollowInstrument.php",
                            data: strData,
                            dataType: 'json',
                            success: function() {
                                alert('取消关注成功');
                            },
                            error: function() {
                                alert('取消关注失败');
                            }
                        });
                    }
                }
            }
        });
        var pageList = new Vue({
            el: '#page_list',
            data: {
                pages_sum: 1,
                current_page: 1,
                focus_page: 1,
                page_size: 5,
                search_key: '',
                search_value: '',
                pages: []
            },
            methods: {
                changePage: function(page_index) {
                    this.$data.current_page = page_index;
                    this.$data.focus_page = page_index;
                    getPageList(this.$data.current_page, this.$data.page_size, instrumentCallBack, strUrl, this.$data.search_key, this.$data.search_value);

                },
                pagePrevious: function() {
                    this.$data.focus_page = this.$data.focus_page - 4;
                    setPagination(this.$data.pages_sum, this.$data.focus_page, this.$data.current_page);
                },
                pageNext: function() {
                    this.$data.focus_page = this.$data.focus_page + 4;
                    setPagination(this.$data.pages_sum, this.$data.focus_page, this.$data.current_page);
                }

            }
        });

        // _csrfgetPageList(1, pageList.$data.page_size, instrumentListCallBack, strUrl);
    </script>
</div>



<div class="modal fade" id="id_modal_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">修改资料-<span><?php echo $strUserName;?></span></h4>
            </div>
            <div class="modal-body c_model_update_body">
                <?php $form = ActiveForm::begin([
                    'action' => ['user/update'],
                    'options'=> ['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => '{input}',
                    ],
                    'enableAjaxValidation' => true,
                ]); ?>
                <!--<form class="form-horizontal">-->
                    <div class="form-group">
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_name" class="col-sm-1 col-xs-12 control-label c_control_label_small">姓名</label>
                            <div class="col-sm-4 col-xs-11">
                               <!-- <input id="id_input_name" class="form-control" required="true" type="text" placeholder="姓名" value="<?php /*echo $strUserName*/?>" />
                                -->
                                <?=$form->field($user_model,'user_name',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_name','class'=>'form-control','required'=>true,'placeholder'=>'姓名','value'=>$strUserName]) ?>

                            </div>
                            <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                            <label for="id_input_user_code" class="col-sm-1 col-xs-12 control-label c_control_label_small">学号</label>
                            <div class="col-sm-4 col-xs-11">
                                <!--<input id="id_input_user_code" class="form-control" required="true" type="text" placeholder="学号" value="<?php /*echo $strUserCode*/?>"/>
                                -->
                                <?= $form->field($user_model,'user_code',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_user_code','class'=>'form-control','required'=>true,'placeholder'=>'学号','value'=>$strUserCode]) ?>
                            </div>
                            <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_select_user_type" class="col-sm-1 col-xs-12 control-label c_control_label_small">人员类型</label>
                            <div class="col-sm-4 col-xs-11">
                               <!-- <select id="id_select_user_type" required="true" class="form-control" >
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
                                </select>-->
                                <?= $form->field($user_model, 'user_type',['options'=>['tag'=>false]])->dropDownList(['0' => '学生',
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
                                ],'id'=>'id_select_user_type', 'value'=>$intUserType,])->hint('') ?>
                            </div>
                            <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                            <label for="id_input_class" class="col-sm-1 col-xs-12 control-label c_control_label_small">专业班级</label>
                            <div class="col-sm-4 col-xs-11">
                               <!-- <input id="id_input_class" class="form-control" type="text" placeholder="专业班级" value="<?php /*echo $strUserClass*/?>"/>
                                -->
                                <?= $form->field($user_model,'user_class',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_class','class'=>'form-control','placeholder'=>'专业班级','value'=>$strUserClass]) ?>

                            </div>
                            <span class="col-sm-1 col-xs-1 c_input_require"></span>
                        </div>

                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_organization" class="col-sm-1 col-xs-12 control-label c_control_label_small">组织机构</label>
                            <div class="col-sm-10 col-xs-11">
                                <input id="id_input_organization" class="form-control" type="text" placeholder="组织机构" readonly="readonly"  value="<?php echo $strOrganization?>"/>
                            </div>
                            <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                        </div>

                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_group" class="col-sm-1 col-xs-12 control-label c_control_label_small">课题组</label>
                            <div class="col-sm-10 col-xs-11">
                                <input id="id_input_group" class="form-control" type="text" placeholder="课题组" readonly="readonly" value="<?= $strGroupName?>"/>
                            </div>
                            <span class="col-sm-1 col-xs-1 c_input_require">*</span>
                        </div>

                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_email" class="col-sm-1 col-xs-12 control-label c_control_label_small">电子邮箱</label>
                            <div class="col-sm-10 col-xs-11">
                                <input id="id_input_email" class="form-control" type="email" placeholder="电子邮箱" readonly="readonly" value="<?= $strUserEmail?>"/>
                            </div>
                            <span class="col-sm-1 col-xs-1 c_input_require"></span>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_phone" class="col-sm-1 col-xs-12 control-label c_control_label_small">联系电话</label>
                            <div class="col-sm-4 col-xs-11">
                                <!--<input id="id_input_phone" class="form-control" type="number" placeholder="联系电话" value="<?php /*echo $strUserPhone*/?>"/>
                                -->
                                <?php echo $form->field($user_model,'phone',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_phone','class'=>'form-control','type'=>'number','placeholder'=>'联系电话','value'=>$strUserPhone]) ?>
                            </div>
                            <span class="col-sm-1 col-xs-1 c_input_require"></span>
                            <label for="id_input_address" class="col-sm-1 col-xs-12 control-label c_control_label_small">地址</label>
                            <div class="col-sm-4 col-xs-11">
                               <!-- <input id="id_input_address" class="form-control" type="text" placeholder="地址" value="<?php /*echo $strUserAddress*/?>"/>
                                -->
                                <?php echo $form->field($user_model,'address',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_address','class'=>'form-control','type'=>'text','placeholder'=>'地址','value'=>$strUserAddress]) ?>
                            </div>
                            <span class="col-sm-1 col-xs-1 c_input_require"></span>
                        </div>
                        <div  class="form-group-sm col-xs-8 col-xs-offset-2">
                            <br/>
                            <br/>
                            <?php  echo Html::submitButton('确定',['class'=>'btn btn-primary btn-block'])?>
                        </div>
                    </div>
                <?php $form = ActiveForm::end(); ?>

                <!--</form>-->


            </div>
        </div>
        <!--<div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        </div>-->
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal -->
</div>
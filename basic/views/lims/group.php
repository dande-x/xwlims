<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '课题组';
$intLoginUserId = isset($this->params['login_user_info']['user_id']) ? $this->params['login_user_info']['user_id'] : 0;
$strUserType =  isset($this->params['login_user_info']['format_user_type']) ? $this->params['login_user_info']['format_user_type'] : '学生';
$intUserType =  isset($this->params['login_user_info']['user_type']) ? $this->params['login_user_info']['user_type'] : 0;

$arrGroupInfo['group_name'] = isset($arrGroupInfo['group_name']) ? $arrGroupInfo['group_name'] : '默认';
$arrGroupInfo['group_id'] = isset($arrGroupInfo['group_id']) ? $arrGroupInfo['group_id'] : 0;
$arrGroupInfo['organization'] = isset($arrGroupInfo['organization']) ? $arrGroupInfo['organization'] : '默认';
$arrGroupInfo['admin_user_name'] = isset($arrGroupInfo['admin_user_name']) ? $arrGroupInfo['admin_user_name'] : '默认';
$arrGroupInfo['description'] = isset($arrGroupInfo['description']) ? $arrGroupInfo['description'] : '默认';

?>

<div>
    <script>
        var strPage = 'group';
        $("#id_sidebar li.active").removeClass("active");
        $("#id_navbar li.c_header_active").removeClass("c_header_active");
        var strDomId = "id_" + strPage + "_page";
        $("#" + strDomId + "_s").addClass("active");
        $("#" + strDomId + "_h").addClass("c_header_active");
    </script>
    <ul id="id_group_tab" class="nav nav-tabs">
        <li class="active">
            <a href="#id_group_list_tab" data-toggle="tab">
                课题组目录
            </a>
        </li>
        <li <?php if ($arrGroupInfo['group_id'] == 0){ echo 'class = "hidden"';} ?>>
            <a href="#id_my_group_tab" data-toggle="tab">
                我的课题组
            </a>
        </li>
    </ul>
    <div id="id_group_tab_content" class="tab-content">
        <div id="id_group_list_tab" class="tab-pane fade in active">
            </br>
            <div class="form-inline row">
                <div class="form-group col-sm-9 row">
                    <input type="text" class="form-control col-xs-8" id="id_search_group_name" placeholder="课题组名称">
                    <a class="btn btn-default col-xs-2 form-control" onclick="searchGroup()">搜索</a>
                </div>
                <button class="btn btn-primary  col-sm-2 <?php
                if ($intUserType != \app\models\User::USER_TYPE_GROUP_ADMIN) {
                    echo 'hidden';
                }
                else if ($intUserType == \app\models\User::USER_TYPE_GROUP_ADMIN && $arrGroupInfo['group_id'] != 0){
                    echo 'hidden';
                }?>"  data-toggle="modal" data-target="#id_modal_add_group">新建课题组</button>
            </div>
            </br>
            <div class="table-responsive">
                <table id="id_group_list" class="table table-striped table-hover">
                    <tr>
                        <td>课题组名称</td>
                        <td>负责人</td>
                        <td>组织机构</td>
                        <td>介绍</td>
                        <td>操作</td>
                    </tr>
                    <?php foreach ($arrGroups as $item):?>
                        <tr class="active">
                            <td><?php echo $item['group_name'];?></td>
                            <td><?php echo $item['admin_user_name'];?></td>
                            <td><?php echo $item['organization'];?></td>
                            <td><?php echo $item['description'];?></td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" onclick="joinGroup(<?=$item['group_id']?>)">申请加入</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                  <!--  <tr v-for="instrument in instruments" class="active">
                        <td>{{group.instrument_name}}</td>
                        <td>{{group.location}}</td>
                        <td>{{group.admin}}</td>
                        <td>{{group.appointment_status}}</td>
                        <td>{{group.appointment_time}}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" v-on:click="apply_group">申请加入</button>
                        </td>
                    </tr>-->
                </table>
            </div>
            <div id="page_list">
                <?php if (!empty($objPage)) { echo yii\widgets\LinkPager::widget(['pagination' => $objPage,]);} ?>
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
        <div id="id_my_group_tab" class="tab-pane fade">
            </br>
            <div class="c_group_container">
                <div class="c_group_info row">
                    <div class="c_group_avatar_container col-md-3  col-sm-3 col-xs-8 col-xs-offset-2">
                        <img class="img-responsive c_group_avatar" src="assets/img/group.jpg" />
                    </div>
                    <div class="c_group_infolist_container col-md-7 col-sm-7 col-xs-12">
                        <ul class=" c_group_infolist list-group">
                            <li class="c_user_name list-group-item">课题组&emsp;&nbsp;:&emsp;<strong><?= $arrGroupInfo['group_name']?></strong></li>
                            <li class="list-group-item">组织机构&nbsp;:&emsp;<strong><?= $arrGroupInfo['organization']?></strong></li>
                            <li class="list-group-item">负责人&emsp;&nbsp;:&emsp;<strong><?= $arrGroupInfo['admin_user_name']?></strong></li>
                            <li class="list-group-item">描述&emsp;&emsp;&nbsp;:&emsp;<strong><?= $arrGroupInfo['description'] ?></strong></li>
                        </ul>
                        <div class="c_group_admin c_group_infobutton">
                            <button class="btn btn-default <?php if ($intUserType != \app\models\User::USER_TYPE_GROUP_ADMIN) {echo 'hidden';}?>"  data-toggle="modal" data-target="#id_modal_update_group" >修改资料</button>
                        </div>
                    </div>
                </div>
                <hr />

                <ul id="id_my_group_tabs" class="nav nav-tabs c_nav_small">
                    <li class="active">
                        <a href="#id_my_group_users_tab" data-toggle="tab">成员列表</a>
                    </li>
                    <li>
                        <a href="#id_my_group_appointment_tab" data-toggle="tab">预约记录</a>
                    </li>
                </ul>

                <div id="id_my_group_tabs_content" class="tab-content">

                    <div id="id_my_group_users_tab" class="tab-pane fade in active">
                        </br>
                        <div class="c_group_below_container">
                            </br>
                            <h3>课题组成员</h3>
                            <form class="form-inline">
                                <div class="form-group">
                                    <select id="id_search_group_user_status" class="form-control">
                                        <option value="1">全部</option>
                                        <option value="2">申请中</option>
                                        <option value="3">已加入</option>
                                    </select>
                                </div>
                                <a class="btn btn-default" onclick="searchGroupUser()">确定</a>
                            </form>
                            <br />
                            <div class="table-responsive">
                                <table id="id_user_list" class="table table-striped table-hover">
                                    <tr>
                                        <td>姓名</td>
                                        <td>人员类型</td>
                                        <td>当前状态</td>
                                        <td>专业班级</td>
                                        <td>联系电话</td>
                                        <td class="c_group_admin" <?php if ($intUserType != \app\models\User::USER_TYPE_GROUP_ADMIN) {echo 'hidden';}?>>操作</td>
                                    </tr>
                                    <?php foreach ($arrGroupUser as $item):?>
                                        <tr class="active">
                                            <td><?php echo $item['user_name'];?></td>
                                            <td><?php echo $item['user_type_format'];?></td>
                                            <td><?php echo $item['group_status_format'];?></td>
                                            <td><?php echo $item['user_class'];?></td>
                                            <td><?php echo $item['phone'];?></td>
                                            <td  <?php if ($intUserType != \app\models\User::USER_TYPE_GROUP_ADMIN) {echo 'hidden';}?>>
                                                <?php
                                                if ( $item['group_status'] == \app\models\GroupUser::STATUS_INIT){
                                                    echo '<button type="button" class="btn btn-info btn-sm" onclick="agreeGroupUserApply('. $item['user_id'] . ')">同意加入</button>';
                                                    echo '<button type="button" class="btn btn-info btn-sm" onclick="disagreeGroupUserApply('. $item['user_id'] . ')">拒绝加入</button>';
                                                }else if ( $item['group_status' ] == \app\models\GroupUser::STATUS_JOIN &&  $item['user_id'] != $intLoginUserId){
                                                    echo '<button type="button" class="btn btn-info btn-sm" onclick="removeGroupUser('. $item['user_id'] . ')">移除成员</button>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>


                                    <!--  <tr v-for="user in users" class="active">
                                          <td>{{user.instrument_name}}</td>
                                          <td>{{user.location}}</td>
                                          <td>{{user.admin}}</td>
                                          <td>{{user.admin}}</td>
                                          <td>{{user.admin}}</td>
                                          <td>{{user.appointment_status}}</td>
                                          <td>{{user.appointment_time}}</td>
                                          <td class="c_group_admin">
                                              <button type="button" class="btn btn-info btn-sm" v-on:click="update_music">移除成员</button>
                                          </td>
                                      </tr>-->
                                </table>
                            </div>
                         <!--  <div id="page_list">
                                <nav aria-label="Page navigation">
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
                                </nav>
                            </div>-->
                        </div>
                    </div>

                    <div id="id_my_group_appointment_tab" class="tab-pane fade">
                        </br>

                        <div class="table-responsive">
                            <table id="id_group_list" class="table table-striped table-hover">
                                <tr>
                                    <td>主题</td>
                                    <td>预约者</td>
                                    <td>仪器名称</td>
                                    <td>预约时间</td>
                                    <td>状态</td>
                                    <td>花费</td>
                                    <td>反馈</td>
                                </tr>
                                <?php foreach ($arrAppointment as $item):?>
                                    <tr class="active">
                                        <td><?= $item['theme'];?></td>
                                        <td><?= $item['user_name'];?></td>
                                        <td><?= $item['instrument_name'];?></td>
                                        <td><?= $item['time_format'];?></td>
                                        <td><?= $item['status_format'];?></td>
                                        <td><?= $item['expenses'] .' 元';?></td>
                                        <td><?= $item['appointment_feedback'];?></td>
                                    </tr>
                                <?php endforeach; ?>


                                <!--<tr v-for="instrument in instruments" class="active">
                                    <td>{{group.instrument_name}}</td>
                                    <td>{{group.location}}</td>
                                    <td>{{group.admin}}</td>
                                    <td>{{group.appointment_status}}</td>
                                    <td>{{group.appointment_time}}</td>
                                </tr>-->
                            </table>
                        </div>
                        <div id="page_list">
                            <?php echo yii\widgets\LinkPager::widget(['pagination' => $objAppointmentPage,]) ?>
                           <!-- <nav aria-label="Page navigation">
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

            </div>
        </div>
    </div>

    <script>
        function searchGroup() {
            var strGroupName = document.getElementById('id_search_group_name').value;
            if ('' == strGroupName){
                window.location.href="index.php?r=lims/group";
                return false;
            }
            window.location.href="index.php?r=lims/group&search_group_name=" + strGroupName;
        }
        function searchGroupUser() {
            var intStatus = parseInt(document.getElementById('id_search_group_user_status').value);
            if (1 == intStatus){
                window.location.href="index.php?r=lims/group";
                return false;
            }
            window.location.href="index.php?r=lims/group&search_group_user_status=" + intStatus;
        }

        function joinGroup(intGroupId) {
            var bolConfirm = confirm("加入新课题组将会退出当前课题组，并且需要课题组负责人审核，确认进行此操作？");
            if (bolConfirm == true)
            {
                window.location.href="index.php?r=group/join&group_id=" + intGroupId;
            }
        }
        function agreeGroupUserApply(intUserId)
        {
            intUserId = parseInt(intUserId);
            if (intUserId != 0){
                window.location.href="index.php?r=group/agree-apply&user_id=" + intUserId + "&group_id=" + <?=$arrGroupInfo['group_id']?>;
            }
        }

        function disagreeGroupUserApply(intUserId)
        {
            intUserId = parseInt(intUserId);
            if (intUserId != 0){
                window.location.href="index.php?r=group/disagree-apply&user_id=" + intUserId + "&group_id=" + <?=$arrGroupInfo['group_id']?>;
            }
        }

        function removeGroupUser(intUserId)
        {
            intUserId = parseInt(intUserId);
            if (intUserId != 0){
                window.location.href="index.php?r=group/remove-group-user&user_id=" + intUserId + "&group_id=" + <?=$arrGroupInfo['group_id']?>;
            }
        }

        document.getElementById('id_search_group_name').value = getUrlParam('search_group_name');
        var intSearchGroupUserStatus = getUrlParam('search_group_user_status');
        if('' != intSearchGroupUserStatus){
            $("#id_search_group_user_status").val(getUrlParam('search_group_user_status'));
        }

    </script>





<div class="modal fade" id="id_modal_add_group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">新建课题组</h4>
            </div>

            <div class="modal-body c_model_update_body">

                <?php $form = ActiveForm::begin([
                    'action' => ['group/add'],
                    'options'=> ['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => '{input}',
                    ],
                ]); ?>
                <div class="form-group">
                    <div class="form-group-sm col-xs-12">
                        <label for="id_input_group_name" class="col-sm-3 col-xs-12 control-label c_control_label_small">课题组名称</label>
                        <div class="col-sm-9 col-xs-11">
                            <?=$form->field($objGroupModel,'group_name',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_group_name','class'=>'form-control','required'=>true,'placeholder'=>'课题组名称']) ?>
                        </div>
                        <br/>
                        <br/>
                        <label for="id_input_organization" class="col-sm-3 col-xs-12 control-label c_control_label_small">组织机构</label>
                        <div class="col-sm-9 col-xs-11">
                            <select id="id_input_organization" class="form-control" required="true" name = "Group[organization_id]">
                                <?php foreach ($arrOrganizationMap as $intKey => $strItem): ?>
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
                        <br/>
                        <br/>

                        <label for="id_input_group_description" class="col-sm-3 col-xs-12 control-label c_control_label_small">简介</label>
                        <div class="col-sm-9 col-xs-11">
                            <?= $form->field($objGroupModel,'description',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_group_description','class'=>'form-control','placeholder'=>'简介']) ?>
                        </div>
                    </div>
                    <br/>
                    <br/>
                    <div  class="form-group-sm col-xs-6 col-xs-offset-3">
                        <br/>
                        <br/>
                        <?php  echo Html::submitButton('确定',['class'=>'btn btn-primary btn-block'])?>
                    </div>
                </div>
                <?php $form = ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
</div>



<div class="modal fade" id="id_modal_update_group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">课题组-修改资料</h4>
            </div>
            <div class="modal-body c_model_update_body">
                <?php $form = ActiveForm::begin([
                    'action' => ['group/update'],
                    'options'=> ['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => '{input}',
                    ],
                ]); ?>
                <div class="form-group">
                    <div class="form-group-sm col-xs-12">
                        <label for="id_input_group_name" class="col-sm-3 col-xs-12 control-label c_control_label_small">课题组名称</label>
                        <div class="col-sm-9 col-xs-11">
                            <?=$form->field($objGroupModel,'group_id',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_group_id','class'=>'form-control hidden','required'=>true,'value'=>$arrGroupInfo['group_id']]) ?>
                            <?=$form->field($objGroupModel,'group_name',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_group_name','class'=>'form-control','required'=>true,'placeholder'=>'课题组名称','value'=>$arrGroupInfo['group_name']]) ?>
                        </div>
                        <br/>
                        <br/>
                        <label for="id_input_organization" class="col-sm-3 col-xs-12 control-label c_control_label_small">组织机构</label>
                        <div class="col-sm-9 col-xs-11">
                                <input id="id_input_organization" class="form-control" type="text" placeholder="组织机构" readonly="readonly"  value="<?=$arrGroupInfo['organization']?>"/>
                        </div>
                        <br/>
                        <br/>

                        <label for="id_input_group_description" class="col-sm-3 col-xs-12 control-label c_control_label_small">简介</label>
                        <div class="col-sm-9 col-xs-11">
                            <?= $form->field($objGroupModel,'description',['options'=>['tag'=>false]])->textInput(['id'=>'id_input_group_description','class'=>'form-control','placeholder'=>'简介','value'=>$arrGroupInfo['description']]) ?>
                        </div>
                    </div>
                    <br/>
                    <br/>
                    <div  class="form-group-sm col-xs-6 col-xs-offset-3">
                        <br/>
                        <br/>
                        <?php  echo Html::submitButton('确定',['class'=>'btn btn-primary btn-block'])?>
                    </div>
                </div>
                <?php $form = ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
</div>
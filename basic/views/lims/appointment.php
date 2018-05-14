<?php
/* @var $this yii\web\View */

$this->title = '预约';
$intUserType =  isset($this->params['login_user_info']['user_type']) ? $this->params['login_user_info']['user_type'] : 0;

?>

<div>
    <script>
        var strPage = 'appointment';
        $("#id_sidebar li.active").removeClass("active");
        $("#id_navbar li.c_header_active").removeClass("c_header_active");
        var strDomId = "id_" + strPage + "_page";
        $("#" + strDomId + "_s").addClass("active");
        $("#" + strDomId + "_h").addClass("c_header_active");
    </script>
    <ul id="id_appointment_tab" class="nav nav-tabs">
        <li class="active">
            <a href="#id_current_appointment_tab" data-toggle="tab">当前预约</a>
        </li>
        <li>
            <a href="#id_appointment_history_tab" data-toggle="tab">预约记录</a>
        </li>
        <li id="id_appointment_admin_tab_li" <?php if (\app\models\User::USER_TYPE_INSTRUMENT_ADMIN !=  $intUserType){echo 'class = "hidden"';}?>>
            <a href="#id_appointment_admin_tab" data-toggle="tab">负责仪器的预约</a>
        </li>
        <li id="id_appointment_blacklist_tab_li" <?php if (\app\models\User::USER_TYPE_INSTRUMENT_ADMIN !=  $intUserType){echo 'class = "hidden"';}?>>
            <a href="#id_appointment_blacklist_tab" data-toggle="tab">黑名单</a>
        </li>
    </ul>

    <div id="id_appointment_tab_content" class="tab-content">
        <div id="id_current_appointment_tab" class="tab-pane fade in active">
            </br>
            <!--当前预约-->
            <div class="table-responsive">
                <table id="id_group_list" class="table table-striped table-hover">
                    <tr>
                        <td>主题</td>
                        <td>仪器名称</td>
                        <td>预约时间</td>
                        <td>状态</td>
                        <td>花费</td>
                        <td>操作</td>
                    </tr>
                    <?php
                    if (empty($arrCurrentAppointment)){
                        $arrCurrentAppointment = array();
                    }
                    foreach ($arrCurrentAppointment as $item):?>
                        <tr class="active">
                            <td><?= $item['theme'];?></td>
                            <td><?= $item['instrument_name'];?></td>
                            <td><?= $item['time_format'];?></td>
                            <td><?= $item['status_format'];?></td>
                            <td><?= $item['expenses'] .' 元';?></td>
                            <td>
                                <div class="c_appointment_item_working <?php if ($item['status'] != \app\models\Appointment::APPOINTMENT_STATUS_INIT) {echo 'hidden';}; ?>">
                                    <!--<button type="button" class="btn btn-primary btn-sm c_btn_listitem" onclick="modifyAppointment(<?/*=$item['appointment_id']*/?>)" >修改</button>-->
                                    <button type="button" class="btn btn-danger btn-sm c_btn_listitem" onclick="cancelAppointment(<?=$item['appointment_id']?>)" >取消</button>
                                </div>
                                <div class="c_appointment_item_working <?php if ($item['status'] != \app\models\Appointment::APPOINTMENT_STATUS_AGREE) {echo 'hidden';}; ?>">
                                    <button type="button" class="btn btn-primary btn-sm c_btn_listitem" onclick="usedAppointment(<?=$item['appointment_id']?>)">使用完毕</button>
                                    <button type="button" class="btn btn-danger btn-sm c_btn_listitem" onclick="cancelAppointment(<?=$item['appointment_id']?>)">取消</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>


                   <!-- <tr v-for="instrument in instruments" class="active">
                        <td>{{group.instrument_name}}</td>
                        <td>{{group.location}}</td>
                        <td>{{group.admin}}</td>
                        <td>{{group.appointment_status}}</td>
                        <td>{{group.appointment_time}}</td>
                        <td>
                            <div class="c_appointment_item_working">
                                <button type="button" class="btn btn-primary btn-sm c_btn_listitem" v-on:click="muti">修改</button>
                                <button type="button" class="btn btn-danger btn-sm c_btn_listitem" v-on:click="delete">取消</button>
                            </div>
                            <div class="c_appointment_item_feedback">
                                <button type="button" class="btn btn-primary btn-sm c_btn_listitem" v-on:click="feedback">填写反馈</button>
                            </div>
                        </td>
                    </tr>-->
                </table>
            </div>

        </div>
        <div id="id_appointment_history_tab" class="tab-pane fade">
            <!--预约记录-->
            <br />
            <div class="table-responsive">
                <table id="id_group_list" class="table table-striped table-hover">
                    <tr>
                        <td>主题</td>
                        <td>仪器名称</td>
                        <td>课题组</td>
                        <td>预约时间</td>
                        <td>状态</td>
                        <td>花费</td>
                        <td>反馈</td>
                    </tr>
                    <?php foreach ($arrAppointmentHistory as $item):?>
                        <tr class="active">
                            <td><?= $item['theme'];?></td>
                            <td><?= $item['instrument_name'];?></td>
                            <td><?= $item['group_name'];?></td>
                            <td><?= $item['time_format'];?></td>
                            <td><?= $item['status_format'];?></td>
                            <td><?= $item['expenses'] .' 元';?></td>
                            <td><?= $item['appointment_feedback'];?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div id="page_list">
                <?php echo yii\widgets\LinkPager::widget(['pagination' => $objAppointmentHistoryPage,]) ?>
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

        <div id="id_appointment_admin_tab" class="tab-pane fade">
            </br>
            <!-- 管理员负责的仪器 -->
            <div class="table-responsive">
                <table id="id_group_list" class="table table-striped table-hover">
                    <tr>
                        <td>主题</td>
                        <td>仪器名称</td>
                        <td>预约人</td>
                        <td>课题组</td>
                        <td>预约时间</td>
                        <td>状态</td>
                        <td>花费</td>
                        <td>反馈</td>
                        <td>操作</td>
                    </tr>
                    <?php foreach ($arrAdminAppointment as $item):?>
                        <tr class="active">
                            <td><?= $item['theme'];?></td>
                            <td><?= $item['instrument_name'];?></td>
                            <td><?= $item['user_name'];?></td>
                            <td><?= $item['group_name'];?></td>
                            <td><?= $item['time_format'];?></td>
                            <td><?= $item['status_format'];?></td>
                            <td><?= $item['expenses'] .' 元';?></td>
                            <td><?= $item['appointment_feedback'];?></td>
                            <td>
                                <div class="c_appointment_item_working">
                                    <?php
                                        $arrBtnText = array();
                                        $arrBtnOption = array(
                                            1 => '',
                                            2 => 'onclick="adminAgreeAppointment('.$item['appointment_id'].')"',
                                            3 => 'onclick="adminDisagreeAppointment('.$item['appointment_id'].')"',
                                            4 => 'onclick="adminDoneAppointment('.$item['appointment_id'].')"',
                                            5 => 'onclick="adminAddBlacklist('.$item['user_id'].')"',
                                        );
                                        if($item['status'] == \app\models\Appointment::APPOINTMENT_STATUS_INIT){
                                            $arrBtnText = array(
                                                // 1 => '修改时间',
                                                2 => '同意',
                                                3 => '拒绝',
                                            );
                                        }else if ($item['status'] == \app\models\Appointment::APPOINTMENT_STATUS_USED){
                                            $arrBtnText = array(
                                                4 => '完成',
                                                5 => '拉黑',
                                            );
                                        }
                                    ?>
                                    <?php
                                    foreach ($arrBtnText as $intKey => $strText){
                                        echo '<button type="button" class="btn btn-primary btn-sm c_btn_listitem" ' . $arrBtnOption[$intKey] .'>' . $strText . '</button>';
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!--<tr v-for="instrument in instruments" class="active">
                        <td>{{group.instrument_name}}</td>
                        <td>{{group.location}}</td>
                        <td>{{group.admin}}</td>
                        <td>{{group.admin}}</td>
                        <td>{{group.admin}}</td>
                        <td>{{group.appointment_status}}</td>
                        <td>{{group.appointment_time}}</td>
                        <td>
                            <div class="c_appointment_item_working">
                                <button type="button" class="btn btn-primary btn-sm c_btn_listitem" v-on:click="muti">修改</button>
                                <button type="button" class="btn btn-danger btn-sm c_btn_listitem" v-on:click="delete">删除</button>
                            </div>
                        </td>
                    </tr>-->
                </table>
            </div>
            <div id="page_list">
                <?php if (!empty($objAdminAppointmentPage)){echo yii\widgets\LinkPager::widget(['pagination' => $objAdminAppointmentPage,]);} ?>
            </div>

        </div>

        <div id="id_appointment_blacklist_tab" class="tab-pane fade">
            </br>
            <!-- 黑名单 -->
            <div class="table-responsive">
                <table id="id_blacklist_list" class="table table-striped table-hover">
                    <tr>
                        <td>用户名</td>
                        <td>拉黑时间</td>
                        <td>操作</td>
                    </tr>
                    <?php foreach ($arrBlacklist as $item):?>
                        <tr class="active">
                            <td><?= $item['user_name'];?></td>
                            <td><?= $item['create_time_format'];?></td>
                            <td>
                                <button class="btn btn-primary" onclick="removeFromBlacklist(<?=$item['user_id'];?>)">移除</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!--<tr v-for="instrument in instruments" class="active">
                        <td>{{group.instrument_name}}</td>
                        <td>{{group.location}}</td>
                        <td>{{group.admin}}</td>
                        <td>{{group.admin}}</td>
                        <td>{{group.admin}}</td>
                        <td>{{group.appointment_status}}</td>
                        <td>{{group.appointment_time}}</td>
                        <td>
                            <div class="c_appointment_item_working">
                                <button type="button" class="btn btn-primary btn-sm c_btn_listitem" v-on:click="muti">修改</button>
                                <button type="button" class="btn btn-danger btn-sm c_btn_listitem" v-on:click="delete">删除</button>
                            </div>
                        </td>
                    </tr>-->
                </table>
            </div>
            <div id="page_list">
                <?php if (!empty($objAdminAppointmentPage)){echo yii\widgets\LinkPager::widget(['pagination' => $objAdminAppointmentPage,]);} ?>
            </div>

        </div>


    </div>
    <script>
        var strUrl = 'index.php?r=appointment';
        function adminAgreeAppointment(intAppointmentId){
            var bolConfirm = confirm("将会同意其该次预约，确认进行此操作？");
            if (bolConfirm == true)
            {
                window.location.href= strUrl + "/agree&appointment_id=" + intAppointmentId;
            }
        }

        function adminDisagreeAppointment(intAppointmentId){
            var bolConfirm = confirm("不同意该次预约，确认进行此操作？");
            if (bolConfirm == true)
            {
                window.location.href= strUrl + "/disagree&appointment_id=" + intAppointmentId;
            }
        }


        function modifyAppointment(intAppointmentId) {

        }

        function cancelAppointment(intAppointmentId) {
            var bolConfirm = confirm("将会取消该次预约，确认进行此操作？");
            if (bolConfirm == true)
            {
                window.location.href= strUrl + "/cancel&appointment_id=" + intAppointmentId;
            }
        }

        function usedAppointment(intAppointmentId) {
            var strFeedback = prompt("请输入使用反馈","");//将输入的内容赋给变量 name ，
            //这里需要注意的是，prompt有两个参数，前面是提示的话，后面是当对话框出来后，在对话框里的默认值
            if(strFeedback)//如果返回的有内容
            {
                window.location.href= strUrl + "/used&appointment_id=" + intAppointmentId + "&feedback=" + strFeedback;
            }
        }

        function adminDoneAppointment(intAppointmentId) {
            var bolConfirm = confirm("将会完成此次预约，请确认预约完成后仪器等没有问题，确认进行此操作？");
            if (bolConfirm == true)
            {
                window.location.href= strUrl + "/done&appointment_id=" + intAppointmentId;
            }
        }

        function adminAddBlacklist(intUserId) {
            var bolConfirm = confirm("将会拉黑该用户，不可再预约您负责的任何仪器（可在黑名单中移除），确认进行此操作？");
            if (bolConfirm == true)
            {
                window.location.href=  "index.php?r=user/add-blacklist&user_id=" + intUserId;
            }
        }

        function removeFromBlacklist(intUserId) {
            var bolConfirm = confirm("将该用户从黑名单中移除，确认进行此操作？");
            if (bolConfirm == true)
            {
                window.location.href=  "index.php?r=user/remove-blacklist&user_id=" + intUserId;
            }
        }
    </script>
</div>


<div class="modal fade" id="id_modal_appointment_admin_agree" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">同意预约</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="modalAppointmentFormSubmit()"> 确定</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
</div>

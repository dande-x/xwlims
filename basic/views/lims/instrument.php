<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '仪器';

/*$this->registerJsFile('@web/assets/js/fullcalendar/fullcalendar.min.js');
$this->registerJsFile('@web/assets/js/fullcalendar/moment.min.js');
$this->registerJsFile('@web/assets/js/timepicker/moment.min.js');
$this->registerJsFile('@web/assets/js/timepicker/daterangepicker.js');*/
$strUserType =  isset($this->params['login_user_info']['format_user_type']) ? $this->params['login_user_info']['format_user_type'] : '学生';
$intUserType =  isset($this->params['login_user_info']['user_type']) ? $this->params['login_user_info']['user_type'] : 0;


$arrInstrumentInfo['instrument_name'] = isset($arrInstrumentInfo['instrument_name']) ? $arrInstrumentInfo['instrument_name'] : '默认';
$arrInstrumentInfo['instrument_id'] = isset($arrInstrumentInfo['instrument_id']) ? $arrInstrumentInfo['instrument_id'] : 0;
$arrInstrumentInfo['status_format'] = isset($arrInstrumentInfo['status_format']) ? $arrInstrumentInfo['status_format'] : '默认';
$arrInstrumentInfo['appointment_price'] = isset($arrInstrumentInfo['appointment_price']) ? $arrInstrumentInfo['appointment_price'] : '默认';
$arrInstrumentInfo['model_number'] = isset($arrInstrumentInfo['model_number']) ? $arrInstrumentInfo['model_number'] : '默认';
$arrInstrumentInfo['instrument_code'] = isset($arrInstrumentInfo['instrument_code']) ? $arrInstrumentInfo['instrument_code'] : '默认';
$arrInstrumentInfo['specifications'] = isset($arrInstrumentInfo['specifications']) ? $arrInstrumentInfo['specifications'] : '默认';
$arrInstrumentInfo['address'] = isset($arrInstrumentInfo['address']) ? $arrInstrumentInfo['address'] : '默认';
$arrInstrumentInfo['organization'] = isset($arrInstrumentInfo['organization']) ? $arrInstrumentInfo['organization'] : '默认';
$arrInstrumentInfo['price'] = isset($arrInstrumentInfo['price']) ? $arrInstrumentInfo['price'] : '默认';
$arrInstrumentInfo['admin_user_name'] = isset($arrInstrumentInfo['admin_user_name']) ? $arrInstrumentInfo['admin_user_name'] : '默认';
$arrInstrumentInfo['manufacturer'] = isset($arrInstrumentInfo['manufacturer']) ? $arrInstrumentInfo['manufacturer'] : '默认';
$arrInstrumentInfo['produce_country'] = isset($arrInstrumentInfo['produce_country']) ? $arrInstrumentInfo['produce_country'] : '默认';
$arrInstrumentInfo['manufacture_time_format'] = isset($arrInstrumentInfo['manufacture_time_format']) ? $arrInstrumentInfo['manufacture_time_format'] : '默认';
$arrInstrumentInfo['purchase_time_format'] = isset($arrInstrumentInfo['purchase_time_format']) ? $arrInstrumentInfo['purchase_time_format'] : '默认';
$arrInstrumentInfo['qualification'] = isset($arrInstrumentInfo['qualification']) ? $arrInstrumentInfo['qualification'] : '默认';
$arrInstrumentInfo['instrument_function'] = isset($arrInstrumentInfo['instrument_function']) ? $arrInstrumentInfo['instrument_function'] : '默认';
$arrInstrumentInfo['attachments'] = isset($arrInstrumentInfo['attachments']) ? $arrInstrumentInfo['attachments'] : '默认';
$arrInstrumentInfo['type_number'] = isset($arrInstrumentInfo['type_number']) ? $arrInstrumentInfo['type_number'] : 0;
$arrInstrumentInfo['status'] = isset($arrInstrumentInfo['status']) ? $arrInstrumentInfo['status'] : 0;
$arrInstrumentInfo['is_follow'] = isset($arrInstrumentInfo['is_follow']) ? $arrInstrumentInfo['is_follow'] : 1;
$arrInstrumentInfo['admin_user_id'] = isset($arrInstrumentInfo['admin_user_id']) ? $arrInstrumentInfo['admin_user_id'] : 0;

?>

<div>

    <!--    <?/*=Html::cssFile('@web/assets/css/instrument.css')*/?>
    <?/*=Html::cssFile('@web/assets/css/fullcalendar/fullcalendar.min.css')*/?>
    <?/*=Html::cssFile('@web/assets/css/fullcalendar/fullcalendar.print.min.css')*/?>
    --><?/*=Html::cssFile('@web/assets/css/timepicker/daterangepicker.css')*/?>

    <!--    <?/*=Html::jsFile('@web/assets/js/fullcalendar/fullcalendar.min.js')*/?>
    <?/*=Html::jsFile('@web/assets/js/fullcalendar/moment.min.js')*/?>
    <?/*=Html::jsFile('@web/assets/js/timepicker/moment.min.js')*/?>
    --><?/*=Html::jsFile('@web/assets/js/timepicker/daterangepicker.js')*/?>

    <link rel="stylesheet" href='assets/css/instrument.css'/>
    <link rel="stylesheet" href='assets/css/fullcalendar/fullcalendar.min.css'/>
    <!--    <link rel="stylesheet" href="assets/css/fullcalendar/fullcalendar.print.min.css"/>-->
    <link rel="stylesheet" href="assets/css/timepicker/daterangepicker.css"/>

    <script src='assets/js/fullcalendar/moment.min.js'></script>
    <script src='assets/js/fullcalendar/fullcalendar.min.js'></script>
    <script src="assets/js/timepicker/moment.min.js"></script>
    <script src="assets/js/timepicker/daterangepicker.js"></script>


    <!-- <link rel="stylesheet" type="text/css" href="../css/timepicker/website.css" />
     -->
    <div class="">
        <script>
            var strPage = 'instrument';
            $("#id_sidebar li.active").removeClass("active");
            $("#id_navbar li.c_header_active").removeClass("c_header_active");
            var strDomId = "id_" + strPage + "_page";
            $("#" + strDomId + "_s").addClass("active");
            $("#" + strDomId + "_h").addClass("c_header_active");
        </script>
        <ul id="id_instrument_tab" class="nav nav-tabs">
            <li id="id_instrument_list_tab_title" class="active">
                <a href="#id_instrument_list_tab" data-toggle="tab"> 仪器列表</a>
            </li>
            <li id="id_instrument_info_tab_title">
                <a href="#id_instrument_info_tab" data-toggle="tab" id="id_instrument_info_tab_name">仪器详情</a>
            </li>
        </ul>

        <div id="id_group_tab_content" class="tab-content">
            <div id="id_instrument_list_tab" class="tab-pane fade in active">
                <br/>

                <h3>仪器列表</h3>
                <div class="form-inline">
                    <div class="form-group">
                        <input type="text" class="form-control" id="id_search_instrument_name" placeholder="仪器名称">
                    </div>
                    <div class="form-group <?php if ($intUserType != \app\models\User::USER_TYPE_INSTRUMENT_ADMIN) {echo 'hidden';}?>">
                        <label class="c_checkbox_lable">
                            <input type="checkbox"  id="id_search_instrument_admin"> 管理的仪器</label>
                    </div>
                    <a class="btn btn-default" onclick="searchInstrument()">搜索</a>
                    <button class="btn btn-primary <?php if ($intUserType != \app\models\User::USER_TYPE_SYSTEM_ADMIN) {echo 'hidden';}?>" data-toggle="modal" data-target="#id_modal_add_instrument">添加仪器</button>
                </div>
                <br />
                <div class="table-responsive">
                    <table id="id_user_list" class="table table-striped table-hover">
                        <tr>
                            <td>仪器名称</td>
                            <td>设备编号</td>
                            <td>状态</td>
                            <td>地址</td>
                            <td>组织</td>
                            <td>负责人</td>
                            <td>操作</td>
                        </tr>
                        <?php foreach ($arrInst as $item):?>
                            <tr class="active">
                                <td><?= $item['instrument_name'];?></td>
                                <td><?= $item['instrument_code'];?></td>
                                <td><?= $item['status_format'];?></td>
                                <td><?= $item['address'];?></td>
                                <td><?= $item['organization'];?></td>
                                <td><?= $item['admin_user_name'];?></td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="<?php if ($item['is_follow']){$strBtbUrl = 'user/unfollow';}else{$strBtbUrl= 'user/follow';} echo yii\helpers\Url::to([$strBtbUrl,'instrument_id'=>$item['instrument_id']]) ?>"><?php if ($item['is_follow']){echo '取消关注';}else{echo '关注';}?></a>
                                    <a type="button" class="btn btn-info btn-sm" href="<?php echo yii\helpers\Url::to(['lims/instrument','instrument_id' => $item['instrument_id']]) ?>">详情</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <!--<tr v-for="user in users" class="active c_instrument_listitem">
                            <td>{{user.instrument_name}}</td>
                            <td>{{user.location}}</td>
                            <td>{{user.admin}}</td>
                            <td>{{user.admin}}</td>
                            <td>{{user.admin}}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" v-on:click="apply_group">关注</button>
                            </td>
                        </tr>-->
                    </table>
                </div>
                <div id="page_list">
                    <?php echo yii\widgets\LinkPager::widget(['pagination' => $objInstPage]) ?>
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

            <div id="id_instrument_info_tab" class="tab-pane fade">
                <br />
                <br />
                <div class="c_instrument_userinfo row">
                    <div class="c_instrument_avatar_container col-md-3  col-sm-3 col-xs-8 col-xs-offset-1">
                        <?= Html::img('@web/assets/img/instrument/' . $arrInstrumentInfo['instrument_id'] . '.jpg',['class' => 'img-responsive c_instrument_avatar'], ['alt' => 'Avatar']) ?>
                    </div>
                    <div class="c_instrument_infolist_container col-md-7 col-sm-7 col-xs-12">
                        <ul class=" c_instrument_infolist list-group">
                            <li class="c_user_name list-group-item">仪器名称&nbsp;:&emsp;<strong><?php echo $arrInstrumentInfo['instrument_name'];?></strong></li>
                            <li class="c_user_name list-group-item">状态&emsp;&emsp;&nbsp;:&emsp;<strong><?php echo $arrInstrumentInfo['status_format'];?></strong></li>
                            <li class="c_user_name list-group-item">使用费用&nbsp;:&emsp;<strong><?php echo $arrInstrumentInfo['appointment_price'].' 元/小时';?></strong></li>
                            <li class="list-group-item">型号&emsp;&emsp;&nbsp;:&emsp;<strong><?php echo $arrInstrumentInfo['model_number'];?></strong></li>
                            <li class="list-group-item">仪器编号&nbsp;:&emsp;<strong><?php echo $arrInstrumentInfo['instrument_code'];?></strong></li>
                            <li class="list-group-item">规格&emsp;&emsp;&nbsp;:&emsp;<strong><?php echo $arrInstrumentInfo['specifications'];?></strong></li>
                            <li class="list-group-item">放置地点&nbsp;:&emsp;<strong><?php echo $arrInstrumentInfo['address'];?></strong></li>
                            <li class="list-group-item">所属单位&nbsp;:&emsp;<strong><?php echo $arrInstrumentInfo['organization'];?></strong></li>
                        </ul>
                        <div class=" c_instrument_infobutton row">
                            <!--<button id="id_instrument_info_follow_btn" class="btn btn-primary col-lg-4 col-sm-5 col-xs-5">关注</button>-->
                            <a class="btn btn-primary col-lg-4 col-sm-5 col-xs-5" href="<?php if ($arrInstrumentInfo['is_follow']){$strBtbUrl = 'user/unfollow';}else{$strBtbUrl= 'user/follow';} echo yii\helpers\Url::to([$strBtbUrl,'instrument_id'=>$arrInstrumentInfo['instrument_id']]) ?>"><?php if ($arrInstrumentInfo['is_follow']){echo '取消关注';}else{echo '关注';}?></a>
                            <a class="btn btn-primary col-lg-4 col-lg-offset-2 col-sm-5 col-xs-5 c_margin_left" href="index.php?r=lims/statistics&instrument_id=<?=$arrInstrumentInfo['instrument_id']?>">统计数据</a>
                            <br />
                            <br />
                            <div class="<?php if ($intUserType != \app\models\User::USER_TYPE_SYSTEM_ADMIN && $intUserType != \app\models\User::USER_TYPE_INSTRUMENT_ADMIN ) {echo 'hidden';}?>">
                                <button class="btn btn-danger col-lg-4 col-sm-5 col-xs-5 c_margin_left_clear" data-toggle="modal" data-target="#id_modal_update_instrument">修改资料</button>
                                <button class="btn btn-danger col-lg-4 col-lg-offset-2 col-sm-5 col-xs-5 " data-toggle="modal" data-target="#id_modal_set_instrument_admin">管理设置</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>

                </div>

                <ul id="id_instrument_info_tabs" class="nav nav-tabs c_nav_small">
                    <li class="active">
                        <a href="#id_instrument_info_appointment_tab" data-toggle="tab">预约情况</a>
                    </li>
                    <li>
                        <a href="#id_instrument_info_detail_tab" data-toggle="tab">详细信息</a>
                    </li>
                    <li>
                        <a href="#id_instrument_info_history_tab" data-toggle="tab">使用记录</a>
                    </li>
                </ul>

                <div id="id_instrument_info_tabs_content" class="tab-content">
                    <div id="id_instrument_info_appointment_tab" class="tab-pane fade in active">
                        <br/>
                        <!--预约情况-->

                        <script>
                            $strNowDate = new Date().format("yyyy-MM-dd");
                            console.log($strNowDate);
                            $(document).ready(function() {
                                //document.getElementById('calendar').
                                $('#calendar').
                                fullCalendar({
                                    header: {
                                        left: 'prev,next today',
                                        center: 'title',
                                        right: 'agendaWeek'
                                    },
                                    defaultDate: $strNowDate,
                                    editable: false,
                                    defaultView: 'agendaWeek',
                                    height: 'auto',
                                    allDaySlot: false,
                                    slotEventOverlap: false,
                                    navLinks: false, // can click day/week names to navigate views
                                    slotLabelFormat: "HH:mm",
                                    eventLimit: false, // allow "more" link when too many events
                                    displayEventTime: false,
                                    events: {
                                        url: 'index.php?r=appointment/getappointment&instrument_id=' + <?= "'".$arrInstrumentInfo['instrument_id']."'"?>,
                                        allDay: false,
                                        error: function() {
                                            $('#script-warning').show();
                                        }
                                    },
                                    loading: function(bool) {
                                        $('#loading').toggle(bool);
                                    }
                                });

                            });
                            var fixFcalT = setTimeout("fixFullcalendarStyle()", 1500);

                            function fixFullcalendarStyle() {
                                if($(".fc-axis").css('width') != "32px") {
                                    $(".fc-axis").css("width", "32px");
                                    clearTimeout(fixFcalT);
                                } else {
                                    fixFcalT = setTimeout("fixFullcalendarStyle()", 1500);
                                }
                            }
                        </script>
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <span class="col-lg-8 col-sm-8 col-xs-8"></span>
                                    <button id="id_appointment_button" class="btn btn-info col-lg-2 col-sm-2 col-xs-2 c_btn_appointment" data-toggle="modal" data-target="#id_modal_appointment">+ 预约</button>
                                </div>
                            </div>
                            <div id='calendar' class="col-lg-10 col-sm-12 col-xs-12"></div>
                        </div>

                    </div>
                    <div id="id_instrument_info_detail_tab" class="tab-pane fade">
                        <br/>
                        <ul class=" c_instrument_infolist list-group">
                            <li class="c_user_name list-group-item">仪器名称&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['instrument_name'];?></strong></li>
                            <li class="list-group-item">型号&emsp;&emsp;&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['model_number'];?></strong></li>
                            <li class="list-group-item">仪器编号&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['instrument_code'];?></strong></li>
                            <li class="list-group-item">规格&emsp;&emsp;&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['specifications'];?></strong></li>
                            <li class="list-group-item">放置地点&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['address'];?></strong></li>
                            <li class="list-group-item">所属单位&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['organization'];?></strong></li>
                            <li class="list-group-item">负责人&emsp;&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['admin_user_name'];?></strong></li>
                            <li class="list-group-item">制造国家&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['produce_country'];?></strong></li>
                            <li class="list-group-item">价格&emsp;&emsp;&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['price'].' 元';?></strong></li>
                            <li class="list-group-item">生产厂家&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['manufacturer'];?></strong></li>
                            <li class="list-group-item">出厂日期&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['manufacture_time_format'];?></strong></li>
                            <li class="list-group-item">购置日期&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['purchase_time_format'];?></strong></li>
                            <li class="list-group-item">技术指标&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['qualification'];?></strong></li>
                            <li class="list-group-item">主要功能&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['instrument_function'];?></strong></li>
                            <li class="list-group-item">主要附件及配置&nbsp;:&emsp;<strong><?= $arrInstrumentInfo['attachments'];?></strong></li>
                        </ul>
                    </div>
                    <div id="id_instrument_info_history_tab" class="tab-pane fade">
                        <br/>
                        <!--使用记录-->
                        <div id="id_instrument_history_list_tab" class="tab-pane fade in active">
                            <br />
                            <div class="table-responsive">
                                <table id="id_user_list" class="table table-striped table-hover">
                                    <tr>
                                        <td>主题</td>
                                        <td>预约者</td>
                                        <td>课题组</td>
                                        <td>预约时间</td>
                                        <td>状态</td>
                                        <td>花费</td>
                                        <td>反馈</td>
                                    </tr>
                                    <?php foreach ($arrAppointment as $item):?>
                                        <tr class="active">
                                            <td><?= $item['theme'];?></td>
                                            <td><?= $item['user_name'];?></td>
                                            <td><?= $item['group_name'];?></td>
                                            <td><?= $item['time_format'];?></td>
                                            <td><?= $item['status_format'];?></td>
                                            <td><?= $item['expenses'] .' 元';?></td>
                                            <td><?= $item['appointment_feedback'];?></td>
                                        </tr>
                                    <?php endforeach; ?>


                                    <!-- <tr v-for="user in users" class="active c_instrument_listitem">
                                         <td>{{user.instrument_name}}</td>
                                         <td>{{user.location}}</td>
                                         <td>{{user.admin}}</td>
                                         <td>{{user.admin}}</td>
                                         <td>{{user.admin}}</td>
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
    </div>
</div>

<script>
    function searchInstrument() {
        var strUrl = 'index.php?r=lims/instrument';
        var strSearchName = document.getElementById('id_search_instrument_name').value;
        var bolSearchAdmin = document.getElementById('id_search_instrument_admin').checked;
        if (bolSearchAdmin) {
            window.location.href= strUrl + "&search_instrument_admin=1";
            return true;
        }
        if ('' != strSearchName){
            window.location.href = strUrl + "&search_instrument_name=" + strSearchName;
            return true;
        }
        window.location.href = strUrl;
    }
    document.getElementById('id_search_instrument_name').value = getUrlParam('search_instrument_name');
    document.getElementById('id_search_instrument_admin').checked = (getUrlParam('search_instrument_admin') == 1) ? true : false;

</script>

</div>
<?php

if ($strTab == 'instrument_info'){
    echo '<script>
        $("#id_instrument_list_tab_title").removeClass("active");
        $("#id_instrument_info_tab_title").addClass("active");
        $("#id_instrument_list_tab").removeClass("in active");
        $("#id_instrument_info_tab").addClass("in active");
    </script>';
}else{
    echo '<script>
        $("#id_instrument_info_tab_title").addClass("c_tab_title_hidden");
    </script>';
}


?>
<div class="modal fade" id="id_modal_appointment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">添加预约-<span id="id_modal_appointment_instrument_name"><?= $arrInstrumentInfo['instrument_name'] ?></span></h4>
            </div>
            <div class="modal-body">
                <form id="id_modal_appointment_form" method="post" role="form" class="form-horizontal" action="index.php?r=appointment/apply">
                    <div class="form-group">
                        <label for="id_modal_appointment_input_theme" class="col-sm-3">主题</label>
                        <input type="text" class="col-sm-9 form-control c_dialog_input" name="theme" id="id_modal_appointment_input_theme" placeholder="预约主题" required="required">
                        <label id="id_theme_input_error" class="col-sm-3 c_input_error_notice"></label>
                    </div>
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                    <div class="form-group">
                        <label for="id_modal_appointment_datepicker" class="col-sm-3">预约时间</label>
                        <input id="id_modal_appointment_datepicker" class="col-sm-9 form-control c_dialog_input" type="text" name="daterange" value="2018-03-20 1:30 PM - 2018-04-1 2:00 PM" />

                        <script type="text/javascript">
                            function getFormatDate(d){
                                // return d.getMonth()+1 + '/' + d.getDate() + '/' + d.getFullYear();
                                console.log(d.getFullYear() + '-' + d.getMonth()+1 + '-' + d.getDate());
                                return d.getFullYear() + '-' + (parseInt(d.getMonth()) + 1) + '-' + d.getDate() + ' ' + d.getHours() + ':' + '00';
                            }
                            $(function() {
                                $('input[name="daterange"]').daterangepicker({
                                    timePicker: true,
                                    timePickerIncrement: 30,
                                    timePicker24Hour: true,
                                    minDate: getFormatDate(new Date()),
                                    locale: {
                                        format: 'YYYY-MM-DD H:mm'
                                    }
                                });
                            });
                            Date.prototype.format = function(format) {
                                var o = {
                                    "M+": this.getMonth() + 1, //month
                                    "d+": this.getDate(), //day
                                    "h+": this.getHours(), //hour
                                    "m+": this.getMinutes(), //minute
                                    "s+": this.getSeconds(), //second
                                    "q+": Math.floor((this.getMonth() + 3) / 3), //quarter
                                    "S": this.getMilliseconds() //millisecond
                                }
                                if(/(y+)/.test(format)) {
                                    format = format.replace(RegExp.$1,
                                        (this.getFullYear() + "").substr(4 - RegExp.$1.length))
                                };
                                if(/(A+)/.test(format)) {
                                    var ampm = (this.getHours() >= 12) ? "PM" : "AM";
                                    format = format.replace(RegExp.$1,
                                        ampm)
                                };
                                for(var k in o) {
                                    if(new RegExp("(" + k + ")").test(format))
                                        format = format.replace(RegExp.$1,
                                            RegExp.$1.length == 1 ? o[k] :
                                                ("00" + o[k]).substr(("" + o[k]).length));
                                }
                                return format;
                            }
                            var strStartTime = new Date().format("yyyy-MM-dd h:mm");
                            var strEndTime = new Date(new Date().getTime() + 4 * 60 * 60 * 1000).format("yyyy-MM-dd h:mm");
                            $("#id_modal_appointment_datepicker").val(strStartTime + " - " + strEndTime);
                        </script>
                    </div>
                    <!--<div class="form-group">
                        <label for="id_modal_appointment_input_type" class="col-sm-3">类型</label>
                        <select id="id_modal_appointment_input_type" class="col-sm-9 form-control c_dialog_input">
                            <option>预约</option>
                            <option>送样</option>
                        </select>
                    </div>-->
                    <div class="form-group">
                        <label for="id_modal_appointment_input_comment" class="col-sm-3">备注</label>
                        <input type="text" class="col-sm-9 form-control c_dialog_input" name="appointment_comment" id="id_modal_appointment_input_comment" placeholder="备注">
                    </div>
                    <input type="hidden"  name="instrument_id" id="id_modal_instrument_id" value="<?= $arrInstrumentInfo['instrument_id'] ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="modalAppointmentFormSubmit()"> 确定预约</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
            <script>
                function modalAppointmentFormSubmit() {
                    var form = document.getElementById('id_modal_appointment_form');
                    var strTheme = document.getElementById('id_modal_appointment_input_theme').value;
                    if(strTheme == ''){
                        document.getElementById('id_theme_input_error').innerHTML = '请填写主题';
                        return false;
                    }
                    form.submit();

                }

            </script>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
</div>

<div class="modal fade" id="id_modal_add_instrument" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">添加仪器</h4>
            </div>
            <div class="modal-body">
                <form id="id_add_instrument_form" method="post" role="form" enctype="multipart/form-data" class="form-horizontal" action="index.php?r=instrument/add">
                    <?php /*$form = ActiveForm::begin([
                    'action' => ['instrument/add'],
                    'options'=> ['id' => 'id_add_instrument_form',
                    'class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => '{input}',
                    ],
                ]);*/?>
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                    <div class="form-group">
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_instrument_name" class="col-sm-3 col-xs-12 control-label c_control_label_small">仪器名称</label>
                            <div class="col-sm-9 col-xs-11">
                                <!--$form->field($objInstrumentModel,'instrument_name',['options'=>['tag'=>false]])->
                                textInput(['id'=>'id_input_add_instrument_name','class'=>'form-control','required'=>true,'placeholder'=>'仪器名称'])-->
                                <input type="text"  id="id_input_add_instrument_name" name="Instrument[instrument_name]" class="form-control" required="required" placeholder="仪器名称">
                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_address" class="col-sm-3 col-xs-12 control-label c_control_label_small">地址</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_add_address" name="Instrument[address]" class="form-control" required="required" placeholder="地址">
                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_model_number" class="col-sm-3 col-xs-12 control-label c_control_label_small">型号</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_add_model_number" name="Instrument[model_number]" class="form-control" required="required" placeholder="型号">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_specifications" class="col-sm-3 col-xs-12 control-label c_control_label_small">规格</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_add_specifications" name="Instrument[specifications]" class="form-control" required="required" placeholder="规格">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_price" class="col-sm-3 col-xs-12 control-label c_control_label_small">价格</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_add_price" name="Instrument[price]" class="form-control" required="required" placeholder="价格">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_appointment_price" class="col-sm-3 col-xs-12 control-label c_control_label_small">预约价格（每小时）</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_add_appointment_price" name="Instrument[appointment_price]" class="form-control" required="required" placeholder="预约价格（每小时）">
                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_produce_country" class="col-sm-3 col-xs-12 control-label c_control_label_small">生产国家</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_add_produce_country" name="Instrument[produce_country]" class="form-control"  placeholder="生产国家">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_manufacturer" class="col-sm-3 col-xs-12 control-label c_control_label_small">生产厂家</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text" id="id_input_add_manufacturer" name="Instrument[manufacturer]" class="form-control" required="required" placeholder="生产厂家">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_manufacture_time" class="col-sm-3 col-xs-12 control-label c_control_label_small">出厂时间</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="date" id="id_input_add_manufacture_time" name="Instrument[manufacture_time]" class="form-control" required="required" placeholder="出厂时间">
                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_purchase_time" class="col-sm-3 col-xs-12 control-label c_control_label_small">购置时间</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="date"  id="id_input_add_purchase_time" name="Instrument[purchase_time]" class="form-control" required="required" placeholder="购置时间">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_organization" class="col-sm-3 col-xs-12 control-label c_control_label_small">组织机构</label>
                            <div class="col-sm-9 col-xs-11">
                                <select id="id_input_add_organization" class="form-control" required="required" name = "Instrument[organization_id]">
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
                        </div>

                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_type_number" class="col-sm-3 col-xs-12 control-label c_control_label_small">分类号</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_add_type_number" name="Instrument[type_number]" class="form-control" required="required" placeholder="分类号">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_instrument_code" class="col-sm-3 col-xs-12 control-label c_control_label_small">仪器编号</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_add_instrument_code" name="Instrument[instrument_code]" class="form-control" required="required" placeholder="仪器编号">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_qualification" class="col-sm-3 col-xs-12 control-label c_control_label_small">技术指标</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_add_qualification" name="Instrument[qualification]" class="form-control"  placeholder="技术指标">
                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_instrument_function" class="col-sm-3 col-xs-12 control-label c_control_label_small">主要功能</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_add_instrument_function" name="Instrument[instrument_function]" class="form-control"  placeholder="主要功能">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_attachments" class="col-sm-3 col-xs-12 control-label c_control_label_small">主要附件及配置</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_add_attachments" name="Instrument[attachments]" class="form-control"  placeholder="主要附件及配置">

                            </div>
                            <br/>
                            <br/>
                        </div>


                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_add_image" class="col-sm-3 col-xs-12 control-label c_control_label_small">仪器图片(jpg、jrpg)</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="file"  id="id_input_add_image" name="instrument_image" class="form-control" multiple="multiple" >
                                <img src="" id="id_input_add_image_preview" class="c_add_image_preview">
                            </div>
                            <br/>
                            <br/>
                        </div>

                        <br/>
                        <br/>
                        <div  class="form-group-sm col-xs-6 col-xs-offset-3">
                            <br/>
                            <br/>
                            <?php  echo Html::submitButton('确定',['class'=>'btn btn-primary btn-block'])?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
</div>


<div class="modal fade" id="id_modal_update_instrument" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">修改资料</h4>
            </div>
            <div class="modal-body">
                <form id="id_update_instrument_form" method="post" role="form" enctype="multipart/form-data"  class="form-horizontal" action="index.php?r=instrument/update">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                    <div class="form-group">
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_instrument_name" class="col-sm-3 col-xs-12 control-label c_control_label_small">仪器名称</label>
                            <div class="col-sm-9 col-xs-11">
                                <!--$form->field($objInstrumentModel,'instrument_name',['options'=>['tag'=>false]])->
                                textInput(['id'=>'id_input_update_instrument_name','class'=>'form-control','required'=>true,'placeholder'=>'仪器名称'])-->
                                <input type="text" id="id_input_update_instrument_id"  name="Instrument[instrument_id]" class="form-control hidden" value="<?=$arrInstrumentInfo['instrument_id'] ?>">

                                <input type="text" id="id_input_update_instrument_name" name="Instrument[instrument_name]" class="form-control" value="<?=$arrInstrumentInfo['instrument_name'] ?>" required="required" placeholder="仪器名称">
                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_address" class="col-sm-3 col-xs-12 control-label c_control_label_small">地址</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_update_address" name="Instrument[address]" class="form-control" value="<?=$arrInstrumentInfo['address'] ?>" required="required" placeholder="地址">
                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_status" class="col-sm-3 col-xs-12 control-label c_control_label_small">仪器状态</label>
                            <div class="col-sm-9 col-xs-11">
                                <select id="id_search_group_user_status" class="form-control" name="Instrument[status]" >
                                    <option value="0" <?php if ($arrInstrumentInfo['status'] == 0) {echo 'selected';}?>>正常</option>
                                    <option value="1" <?php if ($arrInstrumentInfo['status'] == 1) {echo 'selected';}?>>仪器故障</option>
                                    <option value="2" <?php if ($arrInstrumentInfo['status'] == 2) {echo 'selected';}?>>废弃</option>
                                </select>
                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_model_number" class="col-sm-3 col-xs-12 control-label c_control_label_small">型号</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_update_model_number" name="Instrument[model_number]" class="form-control" value="<?=$arrInstrumentInfo['model_number'] ?>" required="required" placeholder="型号">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_specifications" class="col-sm-3 col-xs-12 control-label c_control_label_small">规格</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_update_specifications" name="Instrument[specifications]" class="form-control"  value="<?=$arrInstrumentInfo['specifications'] ?>" required="required" placeholder="规格">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_price" class="col-sm-3 col-xs-12 control-label c_control_label_small">价格</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_update_price" name="Instrument[price]" class="form-control" value="<?=$arrInstrumentInfo['price'] ?>" required="required" placeholder="价格">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_appointment_price" class="col-sm-3 col-xs-12 control-label c_control_label_small">预约价格（每小时）</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_update_appointment_price" name="Instrument[appointment_price]" value="<?=$arrInstrumentInfo['appointment_price'] ?>" class="form-control" required="required" placeholder="预约价格（每小时）">
                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_produce_country" class="col-sm-3 col-xs-12 control-label c_control_label_small">生产国家</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_update_produce_country" name="Instrument[produce_country]" value="<?=$arrInstrumentInfo['produce_country'] ?>" class="form-control"  placeholder="生产国家">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_manufacturer" class="col-sm-3 col-xs-12 control-label c_control_label_small">生产厂家</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text" id="id_input_update_manufacturer" name="Instrument[manufacturer]" value="<?=$arrInstrumentInfo['manufacturer'] ?>" class="form-control" required="required" placeholder="生产厂家">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_organization" class="col-sm-3 col-xs-12 control-label c_control_label_small">组织机构</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text" class="form-control" value="<?=$arrInstrumentInfo['organization'] ?>" readonly="readonly">
                            </div>
                            <br/>
                            <br/>
                        </div>

                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_type_number" class="col-sm-3 col-xs-12 control-label c_control_label_small">分类号</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_update_type_number" name="Instrument[type_number]" value="<?=$arrInstrumentInfo['type_number'] ?>" class="form-control" required="required" placeholder="分类号">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_instrument_code" class="col-sm-3 col-xs-12 control-label c_control_label_small">仪器编号</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_update_instrument_code" name="Instrument[instrument_code]" value="<?=$arrInstrumentInfo['instrument_code'] ?>" class="form-control" required="required" placeholder="仪器编号">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_qualification" class="col-sm-3 col-xs-12 control-label c_control_label_small">技术指标</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_update_qualification" name="Instrument[qualification]" value="<?=$arrInstrumentInfo['qualification'] ?>" class="form-control"  placeholder="技术指标">
                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_instrument_function" class="col-sm-3 col-xs-12 control-label c_control_label_small">主要功能</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_update_instrument_function" name="Instrument[instrument_function]" value="<?=$arrInstrumentInfo['instrument_function'] ?>" class="form-control"  placeholder="主要功能">

                            </div>
                            <br/>
                            <br/>
                        </div>
                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_attachments" class="col-sm-3 col-xs-12 control-label c_control_label_small">主要附件及配置</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="text"  id="id_input_update_attachments" name="Instrument[attachments]" value="<?=$arrInstrumentInfo['attachments'] ?>" class="form-control"  placeholder="主要附件及配置">

                            </div>
                            <br/>
                            <br/>
                        </div>

                        <div class="form-group-sm col-xs-12">
                            <label for="id_input_update_image" class="col-sm-3 col-xs-12 control-label c_control_label_small">仪器图片(jpg、jrpg)</label>
                            <div class="col-sm-9 col-xs-11">
                                <input type="file"  id="id_input_update_image" name="instrument_image" class="form-control" multiple="multiple" >
                                <img src="" id="id_input_update_image_preview" class="c_add_image_preview">
                            </div>
                            <br/>
                            <br/>
                        </div>

                        <br/>
                        <br/>
                        <div  class="form-group-sm col-xs-6 col-xs-offset-3">
                            <br/>
                            <br/>
                            <?php  echo Html::submitButton('确定',['class'=>'btn btn-primary btn-block'])?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
</div>

<script>
    $("#id_input_add_image").change(function(){
        var objUrl = getObjectURL(this.files[0]) ;
        console.log("objUrl = "+objUrl) ;
        if (objUrl) {
            $("#id_input_add_image_preview").attr("src", objUrl) ;
        }
    }) ;
    $("#id_input_update_image").change(function(){
        var objUrl = getObjectURL(this.files[0]) ;
        console.log("objUrl = "+objUrl) ;
        if (objUrl) {
            $("#id_input_update_image_preview").attr("src", objUrl) ;
        }
    }) ;

    //建立一個可存取到該file的url
    function getObjectURL(file) {
        var url = null ;
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url ;
    }
</script>


<div class="modal fade" id="id_modal_set_instrument_admin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">设置仪器管理员(系统管理员)-<span id="id_modal_appointment_instrument_name"><?= $arrInstrumentInfo['instrument_name'] ?></span></h4>
            </div>
            <div class="modal-body">
                <div class="form-group-sm col-xs-12">
                    <label for="id_input_set_instrument_admin" class="col-sm-3 col-xs-12 control-label c_control_label_small">管理员</label>
                    <div class="col-sm-9 col-xs-11">
                        <select id="id_input_set_instrument_admin" class="form-control" required="required" name = "admin_user_id">
                            <?php foreach ($arrAdminUser as $intKey => $item): ?>
                                <option value="<?=$intKey?>" <?php if ($arrInstrumentInfo['admin_user_id'] == $intKey) {echo 'selected';}?>><?=$item['user_name']?></option>
                            <?php  endforeach; ?>
                        </select>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="setInstrumentAdmin()">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
        <script>
            function setInstrumentAdmin() {
                var intAdminId = document.getElementById('id_input_set_instrument_admin').value;
                window.location.href = "index.php?r=instrument/set-instrument-admin&admin_user_id=" + intAdminId + "&instrument_id= " + "<?=$arrInstrumentInfo['instrument_id']?>";
            }
        </script>
    </div>
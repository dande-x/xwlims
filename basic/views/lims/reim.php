<?php
/* @var $this yii\web\View */

$this->title = '消息';

?>

<div>
    <script>
        var strPage = 'reim';
        $("#id_sidebar li.active").removeClass("active");
        $("#id_navbar li.c_header_active").removeClass("c_header_active");
        var strDomId = "id_" + strPage + "_page";
        $("#" + strDomId + "_s").addClass("active");
        $("#" + strDomId + "_h").addClass("c_header_active");
    </script>
    <ul id="id_reim_tab" class="nav nav-tabs">
        <li class="active">
            <a href="#id_reim_receipt_tab" data-toggle="tab">报销单</a>
        </li>
        <li>
            <a href="#id_appointment_history_tab" data-toggle="tab">历史纪录</a>
        </li>
    </ul>

    <div id="id_reim_tab_content" class="tab-content">
        <div id="id_reim_receipt_tab" class="tab-pane fade in active">
            </br>
            <!--报销单-->
            <div class="table-responsive">
                <table id="id_group_list" class="table table-striped table-hover">
                    <tr>
                        <td>状态</td>
                        <td>报销单号</td>
                        <td>课题组</td>
                        <td>使用者</td>
                        <td>仪器名称</td>
                        <td>仪器编号</td>
                        <td>金额</td>
                        <td>操作</td>
                    </tr>
                    <tr v-for="instrument in instruments" class="active">
                        <td>{{group.instrument_name}}</td>
                        <td>{{group.location}}</td>
                        <td>{{group.admin}}</td>
                        <td>{{group.appointment_status}}</td>
                        <td>{{group.appointment_status}}</td>
                        <td>{{group.appointment_status}}</td>
                        <td>{{group.appointment_time}}</td>
                        <td>
                            <div class="c_appointment_item_working">
                                <button type="button" class="btn btn-primary btn-sm c_btn_listitem" v-on:click="muti">打印</button>
                            </div>
                            <div class="c_appointment_item_feedback">
                                <button type="button" class="btn btn-primary btn-sm c_btn_listitem" v-on:click="feedback">归档</button>
                            </div>
                        </td>
                    </tr>
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
                        <td>预约时间</td>
                        <td>状态</td>
                        <td>类型</td>
                        <td>花费</td>
                        <td>反馈</td>
                    </tr>
                    <tr v-for="instrument in instruments" class="active">
                        <td>{{group.instrument_name}}</td>
                        <td>{{group.location}}</td>
                        <td>{{group.admin}}</td>
                        <td>{{group.appointment_status}}</td>
                        <td>{{group.appointment_time}}</td>
                    </tr>
                </table>
            </div>
            <div id="page_list">
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
            </div>

        </div>
    </div>

</div>
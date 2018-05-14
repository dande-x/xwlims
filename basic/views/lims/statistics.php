<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '统计';
$strUserType = isset($this->params['login_user_info']['format_user_type']) ? $this->params['login_user_info']['format_user_type'] : '学生';
$intUserType = isset($this->params['login_user_info']['user_type']) ? $this->params['login_user_info']['user_type'] : 0;
if (empty($strTab)) {
    $strTab = '';
}

$arrInstrumentInfo['instrument_name'] = isset($arrInstrumentInfo['instrument_name']) ? $arrInstrumentInfo['instrument_name'] : '默认';
$arrInstrumentInfo['instrument_id'] = isset($arrInstrumentInfo['instrument_id']) ? $arrInstrumentInfo['instrument_id'] : 0;

?>

    <div>

        <script src='assets/js/echarts.js'></script>

        <div class="">
            <script>
                var strPage = 'statistics';
                $("#id_sidebar li.active").removeClass("active");
                $("#id_navbar li.c_header_active").removeClass("c_header_active");
                var strDomId = "id_" + strPage + "_page";
                $("#" + strDomId + "_s").addClass("active");
                $("#" + strDomId + "_h").addClass("c_header_active");
            </script>
            <ul id="id_statistics_tab" class="nav nav-tabs">
                <li id="id_statistics_list_tab_title" class="active">
                    <a href="#id_statistics_list_tab" data-toggle="tab">统计数据</a>
                </li>
                <li id="id_instrument_statistics_info_tab_title">
                    <a href="#id_instrument_statistics_info_tab" data-toggle="tab"
                       id="id_statistics_info_tab_name">仪器统计</a>
                </li>
            </ul>

            <div id="id_tab_content" class="tab-content">
                <div id="id_statistics_list_tab" class="tab-pane fade in active">
                    <br>
                    <div id="id_echarts_top_instrument" style="width: 600px;height:400px;"></div>
                    <script type="text/javascript">
                        var objInstrumentChart = echarts.init(document.getElementById('id_echarts_top_instrument'));
                        // 指定图表的配置项和数据
                        var option = {
                            title: {
                                text: '仪器被预约次数 TOP'
                            },
                            tooltip: {},
                            legend: {
                                data: ['预约']
                            },
                            xAxis: {
                                data: []
                            },
                            yAxis: {},
                            series: [{
                                name: '预约',
                                type: 'bar',
                                data: []
                            }]
                        };
                        // 使用刚指定的配置项和数据显示图表。
                        objInstrumentChart.setOption(option);

                        $.get('index.php?r=statistics/get-top-appointment-instrument').done(function (data) {
                            // 填入数据
                            var arrInstrument = JSON.parse(data);
                            var arrInstrumentCategories = [];
                            var arrInstrumentCount = [];
                            for (var index in arrInstrument) {
                                arrInstrumentCategories.push(arrInstrument[index]['instrument_name']);
                                arrInstrumentCount.push(arrInstrument[index]['count']);
                            }
                            objInstrumentChart.setOption({
                                xAxis: {
                                    data: arrInstrumentCategories
                                },
                                series: [{
                                    // 根据名字对应到相应的系列
                                    name: '预约',
                                    data: arrInstrumentCount
                                }]
                            });
                        });
                    </script>
                    <br>
                    <br>
                    <div id="id_echarts_top_appointment" style="width: 600px;height:400px;"></div>
                    <script type="text/javascript">
                        var objUserChart = echarts.init(document.getElementById('id_echarts_top_appointment'));
                        // 指定图表的配置项和数据
                        var option = {
                            title: {
                                text: '用户预约次数 TOP'
                            },
                            tooltip: {},
                            legend: {
                                data: ['预约']
                            },
                            xAxis: {
                                data: []
                            },
                            yAxis: {},
                            series: [{
                                name: '预约',
                                type: 'bar',
                                data: []
                            }]
                        };
                        // 使用刚指定的配置项和数据显示图表。
                        objUserChart.setOption(option);
                        $.get('index.php?r=statistics/get-top-appointment-user').done(function (data) {
                            // 填入数据
                            var arrUser = JSON.parse(data);
                            var arrUserCategories = [];
                            var arrUserCount = [];
                            for (var index in arrUser) {
                                arrUserCategories.push(arrUser[index]['user_name']);
                                arrUserCount.push(arrUser[index]['count']);
                            }
                            objUserChart.setOption({
                                xAxis: {
                                    data: arrUserCategories
                                },
                                series: [{
                                    // 根据名字对应到相应的系列
                                    name: '预约',
                                    data: arrUserCount
                                }]
                            });
                        });
                    </script>
                    <br>
                    <br>
                    <div id="id_echarts_pie_group" style="width: 600px;height:400px;"></div>
                    <script type="text/javascript">
                        var objGroupChart = echarts.init(document.getElementById('id_echarts_pie_group'));

                        // 指定图表的配置项和数据
                        var option = {
                            title: {
                                text: '课题组预约数量占比',
                            },
                            tooltip: {
                                trigger: 'item',
                                formatter: "{a} <br/>{b} : {c} ({d}%)"
                            },

                        };
                        // 使用刚指定的配置项和数据显示图表。
                        objGroupChart.setOption(option);

                        $.get('index.php?r=statistics/get-group-appointment-count').done(function (data) {
                            // 填入数据
                            var arrGroup = JSON.parse(data);
                            var arrGroupCategories = [];
                            var arrGroupCount = [];
                            for (var index in arrGroup) {
                                arrGroupCategories.push(arrGroup[index]['group_name']);
                                arrGroupCount.push(
                                    {
                                        name: arrGroup[index]['group_name'],
                                        value: arrGroup[index]['count']
                                    }
                                );
                            }
                            objGroupChart.setOption({
                                legend: {
                                    type: 'scroll',
                                    orient: 'vertical',
                                    right: 10,
                                    top: 20,
                                    bottom: 20,
                                    data: arrGroupCategories,
                                },
                                series: [
                                    {
                                        name: '预约次数',
                                        type: 'pie',
                                        radius: '55%',
                                        center: ['40%', '50%'],
                                        data: arrGroupCount,
                                        itemStyle: {
                                            emphasis: {
                                                shadowBlur: 10,
                                                shadowOffsetX: 0,
                                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                                            }
                                        }
                                    }
                                ]
                            });
                        });
                    </script>
                </div>

                <div id="id_instrument_statistics_info_tab" class="tab-pane fade">
                    <br/>
                    <h3><?= $arrInstrumentInfo['instrument_name']?></h3>
                    <br/>
                    <div id="id_echarts_instrument_week_appointment" style="width: 600px;height:400px;"></div>
                    <br/>
                    <br/>

                    <div id="id_echarts_instrument_appointment_user" style="width: 600px;height:400px;"></div>
                    <script type="text/javascript">
                        var objInstrumentUserChart = echarts.init(document.getElementById('id_echarts_instrument_appointment_user'));
                        // 指定图表的配置项和数据
                        var option = {
                            title: {
                                text: '用户预约次数 TOP'
                            },
                            tooltip: {},
                            legend: {
                                data: ['预约']
                            },
                            xAxis: {
                                data: []
                            },
                            yAxis: {},
                            series: [{
                                name: '预约',
                                type: 'bar',
                                data: []
                            }]
                        };
                        // 使用刚指定的配置项和数据显示图表。
                        objInstrumentUserChart.setOption(option);
                        $.get('index.php?r=statistics/get-instrument-appointment-user&instrument_id=<?= $arrInstrumentInfo['instrument_id']?>').done(function (data) {
                            // 填入数据
                            var arrUser = JSON.parse(data);
                            var arrUserCategories = [];
                            var arrUserCount = [];
                            for (var index in arrUser) {
                                arrUserCategories.push(arrUser[index]['user_name']);
                                arrUserCount.push(arrUser[index]['count']);
                            }
                            objInstrumentUserChart.setOption({
                                xAxis: {
                                    data: arrUserCategories
                                },
                                series: [{
                                    // 根据名字对应到相应的系列
                                    name: '预约',
                                    data: arrUserCount
                                }]
                            });
                        });
                    </script>

                    <script type="text/javascript">
                        var objInstrumentWeekChart = echarts.init(document.getElementById('id_echarts_instrument_week_appointment'));
                        // 指定图表的配置项和数据
                        var option = {
                            title: {
                                text: '最近预约次数'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [{
                                type: 'line'
                            }]
                        };
                        // 使用刚指定的配置项和数据显示图表。
                        objInstrumentWeekChart.setOption(option);
                        $.get('index.php?r=statistics/get-instrument-week-appointment&instrument_id=<?= $arrInstrumentInfo['instrument_id']?>').done(function (data) {
                            // 填入数据
                            var arrWeek = JSON.parse(data);
                            var arrInstWeekCategories = [];
                            var arrInstWeekCount = [];
                            for (var index in arrWeek) {
                                arrInstWeekCategories.push(arrWeek[index]['date']);
                                arrInstWeekCount.push(arrWeek[index]['count']);
                            }
                            objInstrumentWeekChart.setOption({
                                xAxis: {
                                    data: arrInstWeekCategories
                                },
                                series: [{
                                    // 根据名字对应到相应的系列
                                    name: '预约',
                                    data: arrInstWeekCount
                                }]
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
<?php
if ($strTab == 'instrument_statistics') {
    echo '<script>
        $("#id_statistics_list_tab_title").removeClass("active");
        $("#id_instrument_statistics_info_tab_title").addClass("active");
        $("#id_statistics_list_tab").removeClass("in active");
        $("#id_instrument_statistics_info_tab").addClass("in active");
    </script>';
} else {
    echo '<script>
        $("#id_instrument_statistics_info_tab_title").addClass("c_tab_title_hidden");
    </script>';
}
?>
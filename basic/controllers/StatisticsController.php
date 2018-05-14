<?php
/**
 * Created by PhpStorm.
 * User: xwlims
 * Date: 2018/5/12
 * Time: 12:12
 */


namespace app\controllers;

use app\models\Instrument;
use app\models\InstrumentAdmin;
use app\models\Organization;
use app\models\User;
use app\models\Group;
use app\models\GroupUser;
use app\models\Appointment;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class StatisticsController extends \yii\web\Controller
{
    public function actionGetTopAppointmentInstrument()
    {
        $objAppointmentModel = new Appointment();
        $arrInput = array(
            'top_num' => 10,
            'start_time' => 0,
        );
        $arrOutput = $objAppointmentModel->getTopAppointmentInstrumentId($arrInput);
        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            $arrInstrument = $arrOutput['data'];
            $arrInstrumentId = array_keys($arrInstrument);
            $arrInstrumentName = Instrument::_getInstrumentName($arrInstrumentId);
            foreach ($arrInstrument as $intInstrumentId => &$item){
                $item['instrument_name'] = $arrInstrumentName[$intInstrumentId]['instrument_name'];
            }
            $arrInstrument = array_values($arrInstrument);
            return json_encode($arrInstrument);
        }
    }

    public function actionGetTopAppointmentUser()
    {
        $objAppointmentModel = new Appointment();
        $arrInput = array(
            'top_num' => 10,
            'start_time' => 0,
        );
        $arrOutput = $objAppointmentModel->getTopAppointmentUserId($arrInput);
        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            $arrUser = $arrOutput['data'];
            $arrUserId = array_keys($arrUser);
            $arrUserName = User::_getUserName($arrUserId);
            foreach ($arrUser as $intUserId => &$item){
                $item['user_name'] = $arrUserName[$intUserId]['user_name'];
            }
            $arrUser = array_values($arrUser);
            return json_encode($arrUser);
        }
    }

    public function actionGetGroupAppointmentCount()
    {
        $objAppointmentModel = new Appointment();
        $arrOutput = $objAppointmentModel->getGroupAppointmentCount(array());
        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            $arrGroup = $arrOutput['data'];
            $arrGroupId = array_keys($arrGroup);
            $arrGroupName = Group::_getGroupName($arrGroupId);
            foreach ($arrGroup as $intGroupId => &$item){
                $item['group_name'] = $arrGroupName[$intGroupId]['group_name'];
            }
            $arrGroup = array_values($arrGroup);
            return json_encode($arrGroup);
        }
    }

    public function actionGetInstrumentAppointmentUser()
    {
        $intInstrumentId= intval(Yii::$app->request->get('instrument_id',0));
        if (empty($intInstrumentId)){
            return $this->goBack(Yii::$app->request->getReferrer());
        }
        $objAppointmentModel = new Appointment();
        $arrInput = array(
            'instrument_id' => $intInstrumentId,
            'top_num' => 10,
            'start_time' => 0,
        );
        $arrOutput = $objAppointmentModel->getTopAppointmentUserId($arrInput);
        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            $arrUser = $arrOutput['data'];
            $arrUserId = array_keys($arrUser);
            $arrUserName = User::_getUserName($arrUserId);
            foreach ($arrUser as $intUserId => &$item){
                $item['user_name'] = $arrUserName[$intUserId]['user_name'];
            }
            $arrUser = array_values($arrUser);
            return json_encode($arrUser);
        }
    }

    public function actionGetInstrumentWeekAppointment()
    {
        $intInstrumentId= intval(Yii::$app->request->get('instrument_id',0));
        if (empty($intInstrumentId)){
            return $this->goBack(Yii::$app->request->getReferrer());
        }

        $intTimeInterval = 86400;
        $intStartTime = strtotime(date("Y/m/d")) - 7 * $intTimeInterval;
        $objAppointmentModel = new Appointment();
        $arrInput = array(
            'instrument_id' => $intInstrumentId,
            'start_time' => $intStartTime,
            'end_time' => strtotime(date("Y/m/d")),
        );
        // 这里是按start_time asc 排序
        $arrOutput = $objAppointmentModel->getAppointmentByDate($arrInput);
        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            $arrAppointment = $arrOutput['data'];
            // 按天划分7天的时间段，算出每段的count
            $intPointLeft = $intStartTime;
            $intPointRight = $intPointLeft + $intTimeInterval;
            $arrCount = array();
            foreach ($arrAppointment as $key => $item){
                if ($item['start_time'] < $intPointLeft){
                    continue;
                }
                if ($item['start_time'] > $intPointRight){
                    $intPointLeft = strtotime(date("Y/m/d",$item['start_time']));
                    $intPointRight = $intPointLeft + $intTimeInterval;
                }
                if ($item['start_time'] < $intPointRight){

                    if (empty($arrCount[$intPointLeft])){
                        $arrCount[$intPointLeft] = 1;
                    }else{
                        $arrCount[$intPointLeft] += 1;
                    }
                    if (!empty($arrAppointment[$key+1])){
                        if ($arrAppointment[$key+1]['start_time'] >= $intPointRight){
                            $intPointLeft = strtotime(date("Y/m/d",$arrAppointment[++$key]['start_time']));
                            $intPointRight = $intPointLeft + $intTimeInterval;
                        }
                    }
                }
            }
            $arrWeekCount = array();
            foreach ($arrCount as $intTime => $intCount)
            {
                $strDate = date('Y/m/d',$intTime);
                $arrWeekCount[] = array(
                    'count' => $intCount,
                    'date' => $strDate,
                );
            }

            return json_encode($arrWeekCount);
        }



    }

}
<?php

namespace app\models;
use Yii;
use \Datetime;

class Appointment extends \yii\db\ActiveRecord
{

    const APPOINTMENT_STATUS_INIT = 0;
    const APPOINTMENT_STATUS_AGREE = 1;
    const APPOINTMENT_STATUS_DISAGREE = 2;
    const APPOINTMENT_STATUS_USED = 3;
    const APPOINTMENT_STATUS_DONE = 4;
    const APPOINTMENT_STATUS_CANCEL = 5;

    static $ARRAY_STATUS = array(
        self::APPOINTMENT_STATUS_INIT,
        self::APPOINTMENT_STATUS_AGREE,
        self::APPOINTMENT_STATUS_DISAGREE,
        self::APPOINTMENT_STATUS_USED,
        self::APPOINTMENT_STATUS_DONE,
        self::APPOINTMENT_STATUS_CANCEL,
    );

    public static function tableName(){
        return "appointment";
    }

    /**
     * 前端展示用标签名
     */
    public function attributeLabels()
    {
        return [
            'theme' => '主题',
            'status' => '预约状态',
            'description' => '简介',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'expenses' => '费用',
            'appointment_comment' => '备注',
        ];
    }

    public function apply($arrInput)
    {
        $intInstrumentId = intval($arrInput['instrument_id']);
        $intUserId = intval($arrInput['user_id']);
        $intGroupId = intval($arrInput['group_id']);
        $strTheme = strval($arrInput['theme']);
        $strComment = strval($arrInput['appointment_comment']);
        $intStartTime = intval($arrInput['start_time']);
        $intEndTime = intval($arrInput['end_time']);
        $intAdminUserId = intval($arrInput['admin_user_id']);

        if (empty($intInstrumentId) || empty($intUserId) ||empty($intGroupId)
            ||empty($strTheme) ||empty($intStartTime) || empty($intEndTime)){
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $intStatus = self::APPOINTMENT_STATUS_INIT;

        $arrInput = array(
            'start_time' => $intStartTime,
            'end_time' => $intEndTime,
            'instrument_id' => $intInstrumentId,
        );
        $arrOutput = self::_getExpenses($arrInput);
        if ($arrOutput['error_code'] != Yii::$app->params['errorCode']['success']){
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }

        $douExpenses = $arrOutput['data']['expenses'];
        $this->instrument_id = $intInstrumentId;
        $this->user_id = $intUserId;
        $this->admin_user_id = $intAdminUserId;
        $this->theme = $strTheme;
        $this->group_id = $intGroupId;
        $this->start_time = $intStartTime;
        $this->end_time = $intEndTime;
        $this->status = $intStatus;
        $this->appointment_comment = $strComment;
        $this->expenses = $douExpenses;

        if ($this->save()){
            return returnFormat(Yii::$app->params['errorCode']['success']);
        }else{
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    public function setAppointmentStatus($arrInput)
    {
        $intAppointmentId = $arrInput['appointment_id'];
        $intStatus = $arrInput['status'];

        if (empty($intStatus) || empty($intAppointmentId) || !in_array($intStatus,self::$ARRAY_STATUS))
        {
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }

        $objAppointment = self::find()->where('appointment_id=:appointment_id',[':appointment_id'=>$intAppointmentId])->one();
        $objAppointment->status = $intStatus;

        if ($objAppointment->save()){
            return returnFormat(Yii::$app->params['errorCode']['success']);
        }else{
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }

    }

    public function setAppointmentUsed($arrInput)
    {
        $intAppointmentId = $arrInput['appointment_id'];
        $intStatus = self::APPOINTMENT_STATUS_USED;
        $strFeedback = $arrInput['appointment_feedback'];

        if ('' == $strFeedback || empty($intAppointmentId))
        {
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }

        $objAppointment = self::find()->where('appointment_id=:appointment_id',[':appointment_id'=>$intAppointmentId])->one();
        $objAppointment->status = $intStatus;
        $objAppointment->appointment_feedback = $strFeedback;

        if ($objAppointment->save()){
            return returnFormat(Yii::$app->params['errorCode']['success']);
        }else{
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    public function getTopAppointmentInstrumentId($arrInput)
    {
        $intTopNumber = !empty($arrInput['top_num']) ? intval($arrInput['top_num']) : 10;
        $intStartTime = !empty($arrInput['start_time']) ? intval($arrInput['start_time']) : 0;
        try{
            $arrRet = Yii::$app->db->createCommand('SELECT instrument_id,count(1) as count FROM appointment
                WHERE start_time > :start_time GROUP BY instrument_id ORDER BY count desc LIMIT :limit')
                ->bindValue(':start_time', $intStartTime)
                ->bindValue(':limit', $intTopNumber)
                ->queryAll();
            $arrInstrument = array();
            foreach ($arrRet as $item){
                $arrInstrument[intval($item['instrument_id'])]['count'] = intval($item['count']);
            }
            return returnFormat(Yii::$app->params['errorCode']['success'],$arrInstrument);
        }catch (\Exception $e){
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    public function getTopAppointmentUserId($arrInput)
    {
        $intTopNumber = !empty($arrInput['top_num']) ? intval($arrInput['top_num']) : 10;
        $intStartTime = !empty($arrInput['start_time']) ? intval($arrInput['start_time']) : 0;
        $intInstrumentId = !empty($arrInput['instrument_id']) ? intval($arrInput['instrument_id']) : 0;
        try{
            $strSql = 'SELECT user_id,count(1) as count FROM appointment ';
            if (!empty($intStartTime)){
                $strSql .= ' WHERE start_time > :start_time ';
            }
            if (!empty($intInstrumentId)){
                if (!empty($intStartTime)){
                    $strSql .= ' AND ';
                }else{
                    $strSql .= ' WHERE ';
                }
                $strSql .= ' instrument_id = :instrument_id ';
            }
            $strSql .= ' GROUP BY user_id ORDER BY count desc LIMIT :limit';
            $arrRet = Yii::$app->db->createCommand($strSql)
                ->bindValue(':limit', $intTopNumber);
            if (!empty($intStartTime)){
                $arrRet = $arrRet->bindValue(':start_time', $intStartTime);
            }
            if (!empty($intInstrumentId)){
                $arrRet = $arrRet->bindValue(':instrument_id', $intInstrumentId);
            }
            $arrRet = $arrRet->queryAll();

            $arrInstrument = array();
            foreach ($arrRet as $item){
                $arrInstrument[intval($item['user_id'])]['count'] = intval($item['count']);
            }
            return returnFormat(Yii::$app->params['errorCode']['success'],$arrInstrument);
        }catch (\Exception $e){
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    public function getGroupAppointmentCount($arrInput)
    {
        try{
            $arrRet = Yii::$app->db->createCommand('SELECT group_id,count(1) as count FROM appointment
                 GROUP BY group_id ORDER BY count desc')
                ->queryAll();
            $arrGroup = array();
            foreach ($arrRet as $item){
                $arrGroup[intval($item['group_id'])]['count'] = intval($item['count']);
            }
            return returnFormat(Yii::$app->params['errorCode']['success'],$arrGroup);
        }catch (\Exception $e){
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    public function getAppointmentByDate($arrInput)
    {
        $intEndTime= !empty($arrInput['end_time']) ? intval($arrInput['end_time']) : 0;
        $intStartTime = !empty($arrInput['start_time']) ? intval($arrInput['start_time']) : 0;
        if (empty($intStartTime) || empty($intEndTime)){
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $intInstrumentId = !empty($arrInput['instrument_id']) ? intval($arrInput['instrument_id']) : 0;

        try{
            $arrRet = self::find()->where('instrument_id=:instrument_id and start_time>:start_time and start_time<=:end_time',
                [':instrument_id' => $intInstrumentId,':start_time'=>$intStartTime,':end_time'=>$intEndTime])->orderBy(['start_time'=>SORT_ASC])->asArray()->all();
            return returnFormat(Yii::$app->params['errorCode']['success'],$arrRet);
        }catch (\Exception $e){
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    public static function _checkTime($intStartTime,$intEndTime)
    {
        $arrData = self::find()->where('start_time<:end_time and end_time>:start_time and status in (:status)',
            [':end_time'=>$intEndTime,':start_time'=>$intStartTime,':status' => implode(array(Appointment::APPOINTMENT_STATUS_INIT,Appointment::APPOINTMENT_STATUS_AGREE,Appointment::APPOINTMENT_STATUS_USED))])
            ->asArray()->one();
        if (!empty($arrData)){
            return false;
        }else{
            return true;
        }
    }

    public static function _getExpenses($arrInput)
    {
        $intStartTime = $arrInput['start_time'];
        $intEndTime = $arrInput['end_time'];
        $intInstrumentId = $arrInput['instrument_id'];

        $arrOutput = Instrument::_getInstrumentInfoById($intInstrumentId);
        if ($arrOutput['error_code'] != Yii::$app->params['errorCode']['success']){
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
        $arrInstrumentInfo = $arrOutput['data'];
        $douAppointmentPrice = $arrInstrumentInfo[0]['appointment_price'];
        $douHour = ($intEndTime - $intStartTime) / 3600.0;
        $douPrice = $douHour * $douAppointmentPrice;
        $arrRet = array(
            'expenses' => $douPrice,
        );
        return returnFormat(Yii::$app->params['errorCode']['success'],$arrRet);
    }

    public static function _getStatusText($intId)
    {
        $strRet = '';
        switch ($intId){
            case self::APPOINTMENT_STATUS_INIT:
                $strRet = '申请中';
                break;
            case self::APPOINTMENT_STATUS_AGREE:
                $strRet = '进行中';
                break;
            case self::APPOINTMENT_STATUS_DISAGREE:
                $strRet = '被管理员拒绝';
                break;
            case self::APPOINTMENT_STATUS_USED:
                $strRet = '使用完毕';
                break;
            case self::APPOINTMENT_STATUS_DONE:
                $strRet = '已完成';
                break;
            case self::APPOINTMENT_STATUS_CANCEL:
                $strRet = '已被取消';
                break;
            default:
                $strRet = '未知';
        }
        return $strRet;
    }

    public static function _formatAppointment($arrAppointment)
    {
        $arrUserId = array();
        $arrGroupId = array();
        $arrInstrumentId = array();
        if (empty($arrAppointment)){
            return $arrAppointment;
        }
        foreach ($arrAppointment as $item){
            $arrUserId[] = $item['user_id'];
            $arrUserId[] = $item['admin_user_id'];
            $arrGroupId[] = $item['group_id'];
            $arrInstrumentId[] = $item['instrument_id'];
        }
        $arrUserName = User::_getUserName($arrUserId);
        $arrGroupName = Group::_getGroupName($arrGroupId);
        $arrInstrumentName = Instrument::_getInstrumentName($arrInstrumentId);

        foreach ($arrAppointment as &$item)
        {
            $item['start_time_format'] = date('Y-m-d H:i',$item['start_time']);
            $item['end_time_format'] = date('Y-m-d H:i',$item['end_time']);
            $item['time_format'] = $item['start_time_format'] . ' - ' . $item['end_time_format'];
            $item['status_format'] = self::_getStatusText($item['status']);
            $item['user_name'] = $arrUserName[$item['user_id']]['user_name'];
            $item['admin_user_name'] = $arrUserName[$item['admin_user_id']]['user_name'];
            $item['group_name'] = $arrGroupName[$item['group_id']]['group_name'];
            $item['instrument_name'] = $arrInstrumentName[$item['instrument_id']]['instrument_name'];
        }
        return $arrAppointment;
    }

    public static function _formatAppointmentToEvent($arrAppointmentEvent)
    {
        $arrRet = array();
        foreach ($arrAppointmentEvent as $item){
            $strStartTime = date_format(date_timestamp_set(new DateTime(), $item['start_time']), 'c');
            $strEndTime = date_format(date_timestamp_set(new DateTime(), $item['end_time']), 'c');
            //$strStartTime = date('Y-m-d',$item['start_time']) . 'T' . date('H::i::s',$item['start_time']);
            //$strEndTime = date('Y-m-d',$item['end_time']) . 'T' . date('H::i::s',$item['end_time']);
            $strTitle = $item['user_name'] . ' -- ' . $item['theme'];
            $intId = $item['appointment_id'];
            $arrRet[] = array(
                'id' => $intId,
                'title' => $strTitle,
                'start' => $strStartTime,
                'end' => $strEndTime,
            );
        }
        return $arrRet;
    }

}
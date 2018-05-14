<?php

namespace app\models;
use Yii;
use app\models\User;

class InstrumentAdmin extends \yii\db\ActiveRecord
{
    public static function tableName(){
        return "instrument_admin";
    }

    public function setInstrumentAdmin($arrInput)
    {
        $intAdminUserId = intval($arrInput['admin_user_id']);
        $intInstId = intval($arrInput['instrument_id']);

        if (empty($intAdminUserId) || empty($intInstId)){
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }

        $objAdmin = self::find()->where(['user_id' => $intAdminUserId])->one();
        if (!empty($objAdmin)){
            if (!$objAdmin->delete()){
                return returnFormat(Yii::$app->params['errorCode']['fail']);
            }
        }
        $this->user_id = $intAdminUserId;
        $this->instrument_id = $intInstId;
        if ($this->save()){
            return returnFormat(Yii::$app->params['errorCode']['success']);
        }else{
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }

    }

    public function getInstrumentAdminByInst($arrInstrumentId)
    {
        if (empty($arrInstrumentId) || !is_array($arrInstrumentId))
        {
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $arrAdmin = self::find()->indexBy('instrument_id')->where(['instrument_id' => $arrInstrumentId])->asArray()->all();
        return returnFormat(Yii::$app->params['errorCode']['success'],$arrAdmin);
    }

    public static function getInstrumentByAdmin($intAdminId)
    {
        if (empty($intAdminId))
        {
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $arrInstrument = self::find()->where(['user_id' => $intAdminId])->asArray()->all();
        $arrInstrumentId = array();
        foreach ($arrInstrument as $item){
            $arrInstrumentId[] = $item['instrument_id'];
        }
        return returnFormat(Yii::$app->params['errorCode']['success'],$arrInstrumentId);
    }

}
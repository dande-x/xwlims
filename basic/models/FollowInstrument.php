<?php

namespace app\models;
use Yii;

class FollowInstrument extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return "follow_instrument";
    }

    public function addFollow($arrInput)
    {
        $intInstrumentId = intval($arrInput['instrument_id']);
        $intUserId = intval($arrInput['user_id']);

        $arrFollowInfo = self::find()->where('user_id=:user_id and instrument_id=:instrument_id',[':user_id'=>$intUserId,':instrument_id'=>$intInstrumentId])->asArray()->one();
        if (null == $arrFollowInfo){
            $this->user_id = $intUserId;
            $this->instrument_id = $intInstrumentId;
            $this->save();
        }
        return returnFormat(Yii::$app->params['errorCode']['success']);
    }

    public function unfollow($arrInput)
    {
        $intInstrumentId = intval($arrInput['instrument_id']);
        $intUserId = intval($arrInput['user_id']);

        $arrFollowInfo = self::find()->where('user_id=:user_id and instrument_id=:instrument_id',[':user_id'=>$intUserId,':instrument_id'=>$intInstrumentId])->one();
        if (null != $arrFollowInfo){
            $arrFollowInfo->delete();
        }
        return returnFormat(Yii::$app->params['errorCode']['success']);
    }

    public function getFollow($arrInput)
    {
        $intInstrumentId = intval($arrInput['instrument_id']);
        $intUserId = intval($arrInput['user_id']);

        $arrFollowInfo = self::find()->where('user_id=:user_id and instrument_id=:instrument_id',[':user_id'=>$intUserId,':instrument_id'=>$intInstrumentId])->one();
        if (null != $arrFollowInfo){
            $intIsFollow = 1;
        }else{
            $intIsFollow = 0;
        }
        return returnFormat(Yii::$app->params['errorCode']['success'],array('is_follow' => $intIsFollow));
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: zhang han qing
 * Date: 2018/4/26
 * Time: 19:13
 */

namespace app\models;
use Yii;

class UserBlacklist extends \yii\db\ActiveRecord
{
    // 该仪器管理员下所有仪器
    const BLACKLIST_TYPE_ADMIN = 1;
    // 对单个仪器
    const BLACKLIST_TYPE_INSTRUMENT = 2;

    private static $arrStatus = array(
        self::BLACKLIST_TYPE_ADMIN,
        self::BLACKLIST_TYPE_INSTRUMENT,
    );

    public static function tableName(){
        return "user_blacklist";
    }

    public function addBlacklist($arrInput)
    {
        $intAdminId = intval($arrInput['admin_user_id']);
        $intUserId = intval($arrInput['user_id']);
        $intBlacklistType = intval($arrInput['blacklist_type']);
        $intInstrumentId = intval($arrInput['instrument_id']);
        if (empty($intUserId) || empty($intAdminId) || !in_array($intBlacklistType,self::$arrStatus)){
            returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        if ($intBlacklistType == self::BLACKLIST_TYPE_INSTRUMENT && empty($intInstrumentId)){
            returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $this->admin_user_id = $intAdminId;
        $this->user_id = $intUserId;
        $this->blacklist_type = $intBlacklistType;
        $this->instrument_id = $intInstrumentId;
        $this->create_time = time();

        if ($this->save()){
            return returnFormat(Yii::$app->params['errorCode']['success']);
        }else{
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }

    }


    public function removeBlacklist($arrInput)
    {
        $intAdminId = intval($arrInput['admin_user_id']);
        $intUserId = intval($arrInput['user_id']);
        if (empty($intUserId) || empty($intAdminId)){
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $objBlacklistItem = self::find()->where(['user_id'=>$intUserId,'admin_user_id'=>$intAdminId])->one();

        if ($objBlacklistItem->delete()){
            return returnFormat(Yii::$app->params['errorCode']['success']);
        }else{
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }

    }


    public static function _checkBlacklist($intUserId,$intAdminUserId,$intInstrumentId)
    {
        if (empty($intUserId) || empty($intAdminUserId) || empty($intInstrumentId))
        {
            return false;
        }
        $arrAdminType = self::find()->where(['user_id' => $intUserId,'admin_user_id' => $intAdminUserId,'blacklist_type'=> self::BLACKLIST_TYPE_ADMIN])->asArray()->one();
        if (!empty($arrAdminType)){
            return false;
        }
        $arrInstrumentType = self::find()->where(['user_id' => $intUserId,'instrument_id' => $intInstrumentId,'blacklist_type'=> self::BLACKLIST_TYPE_INSTRUMENT])->asArray()->one();
        if (!empty($arrInstrumentType)){
            return false;
        }
        return true;
    }

    public static function _formatBlacklist($arrList)
    {
        $arrUserId = array();
        foreach ($arrList as $item) {
            $arrUserId[] = $item['user_id'];
        }
        $arrUserName = User::find()->select(['user_id','user_name'])->indexBy('user_id')->asArray()->where( ['user_id' => $arrUserId])->all();
        foreach ($arrList as &$item) {
            $item['create_time_format'] = date('Y/m/d H:i:s',$item['create_time']);
            $item['user_name'] = isset($arrUserName[$item['user_id']]['user_name']) ? $arrUserName[$item['user_id']]['user_name'] : '';
        }
        return $arrList;
    }


}
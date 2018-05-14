<?php

namespace app\models;
use Yii;

class GroupUser extends \yii\db\ActiveRecord
{
    const STATUS_INIT = 0;
    const STATUS_JOIN = 1;

    private static $arrStatus = array(
        0 => '待审核',
        1 => '已加入',
    );
    public static function tableName(){
        return "group_user";
    }

    public static function _getStatusText($intStatus)
    {
        if (isset(self::$arrStatus[$intStatus])){
            return self::$arrStatus[$intStatus];
        }else{
            return '默认';
        }
    }

    public static function _getInitStatus()
    {
        return 0;
    }

    public static function _getUserGroupId($intUserId)
    {
        $arrGroupUser = self::find()->asArray()->where(['user_id'=>$intUserId])->one();
        $intGroupId = $arrGroupUser['group_id'];
        return $intGroupId;
    }

    public static function _getUserJoinGroupId($intUserId)
    {
        $arrGroupUser = self::find()->asArray()->where(['user_id'=>$intUserId,'status'=>self::STATUS_JOIN])->one();
        $intGroupId = $arrGroupUser['group_id'];
        return $intGroupId;
    }

    public static function _getUserGroupInfo($intUserId)
    {
        $arrGroupUser = self::find()->asArray()->where(['user_id'=>$intUserId])->one();
        $intGroupId = $arrGroupUser['group_id'];
        $intStatus = $arrGroupUser['status'];
        $arrRet = array(
            'group_id' => $intGroupId,
            'status' => $intStatus,
            'status_format' => self::_getStatusText($intStatus),
        );
        return $arrRet;
    }

}
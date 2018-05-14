<?php
/**
 * Created by PhpStorm.
 * User: xwlims
 * Date: 2018/5/1
 * Time: 20:27
 */

namespace app\models;
use Yii;

class Message extends \yii\db\ActiveRecord
{
    const MESSAGE_STATUS_UNREAD = 0;
    const MESSAGE_STATUS_READ = 1;

    public static function tableName(){
        return "message";
    }

    public function addMessage($arrInput)
    {
        $intUserId = intval($arrInput['user_id']);
        $strContent = strval($arrInput['content']);
        $intTime = isset($arrInput['create_time']) ? intval($arrInput['create_time']) : time();
        if (empty($intUserId) || empty($strContent)){
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $this->user_id = $intUserId;
        $this->content = $strContent;
        $this->create_time = $intTime;
        if ($this->save()){
            return returnFormat(Yii::$app->params['errorCode']['success']);
        }else{
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    public static function _formatMessage($arrList)
    {
        foreach ($arrList as &$item) {
            $item['create_time_format'] = date('Y/m/d H:i:s',$item['create_time']);
        }
        return $arrList;
    }

}
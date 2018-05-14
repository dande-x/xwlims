<?php

namespace app\models;

use Yii;
use app\models\User;

class Group extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return "group_info";
    }

    public function rules()
    {
        return [
            ['group_name', 'required', 'message' => '不可为空'],
            ['organization_id', 'required', 'message' => '不可为空'],
            ['description', 'required', 'message' => '不可为空'],
            ['group_admin_id', 'required', 'message' => '不可为空'],
        ];
    }

    public function addGroup($arrData)
    {
        if ($this->load($arrData) && $this->validate()) {
            if ($this->save()) {
                $intUserId = $arrData['Group']['group_admin_id'];
                $arrGroupId = self::find()->select('group_id')->where('group_admin_id=:group_admin_id', [':group_admin_id' => $intUserId])->asArray()->one();
                $intGroupId = $arrGroupId['group_id'];
                $objGroupUserModel = new GroupUser();
                $objGroupUserModel->group_id = $intGroupId;
                $objGroupUserModel->user_id = $intUserId;
                $objGroupUserModel->status = GroupUser::STATUS_JOIN;
                $objGroupUserModel->join_time = time();
                $objGroupUserModel->save();
                return returnFormat(Yii::$app->params['errorCode']['success']);
            } else {
                return returnFormat(Yii::$app->params['errorCode']['fail']);
            }
        }
        return returnFormat(Yii::$app->params['errorCode']['fail']);
    }

    public function joinGroup($arrInput)
    {
        $intUserId = $arrInput['user_id'];
        $intGroupId = $arrInput['group_id'];
        if (empty($intGroupId) || empty($intUserId)) {
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $objGroupUser = GroupUser::find()->where('user_id=:user_id', [':user_id' => $intUserId])->one();
        if (!empty($objGroupUser) && $objGroupUser->group_id == $intGroupId) {
            return returnFormat(Yii::$app->params['errorCode']['success']);
        }
        if (!empty($objGroupUser) && !$objGroupUser->delete()) {
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }

        $objGroupUserModel = new GroupUser();
        $objGroupUserModel->group_id = $intGroupId;
        $objGroupUserModel->user_id = $intUserId;
        $objGroupUserModel->status = GroupUser::STATUS_INIT;
        $objGroupUserModel->join_time = time();
        if ($objGroupUserModel->save()) {
            return returnFormat(Yii::$app->params['errorCode']['success']);
        } else {
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    public function updateGroup($arrData)
    {
        $intUserId = $arrData['update_user_id'];
        $intGroupId = $arrData['Group']['group_id'];
        $objGroupInfo = $this->find()->where('group_id=:group_id', [':group_id' => $intGroupId])->one();
        if (empty($objGroupInfo) || $objGroupInfo->group_admin_id != $intUserId) {
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
        $objGroupInfo->group_name = $arrData['Group']['group_name'];
        $objGroupInfo->description = $arrData['Group']['description'];
        if ($objGroupInfo->save()) {
            return returnFormat(Yii::$app->params['errorCode']['success']);
        } else {
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    public function agreeApply($arrInput)
    {
        $intUserId = $arrInput['user_id'];
        $intApplyUserId = $arrInput['apply_user_id'];
        $intGroupId = $arrInput['group_id'];
        if (empty($intApplyUserId) || empty($intUserId) || empty($intGroupId)|| !self::_checkUserIsGroupAdmin($intUserId,$intGroupId)) {
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $objGroupUser = GroupUser::find()->where('user_id=:user_id and group_id=:group_id',[':user_id'=>$intApplyUserId,':group_id'=>$intGroupId])->one();
        $objGroupUser->status = GroupUser::STATUS_JOIN;
        if ($objGroupUser->save()) {
            return returnFormat(Yii::$app->params['errorCode']['success']);
        } else {
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    public function disagreeApply($arrInput)
    {
        $intUserId = $arrInput['user_id'];
        $intApplyUserId = $arrInput['apply_user_id'];
        $intGroupId = $arrInput['group_id'];
        if (empty($intApplyUserId) || empty($intUserId) || empty($intGroupId)|| self::_checkUserIsGroupAdmin($intUserId,$intGroupId)) {
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $objGroupUser = GroupUser::find()->where('user_id=:user_id and group_id=:group_id',[':user_id'=>$intApplyUserId,':group_id'=>$intGroupId])->one();
        if ($objGroupUser->delete()) {
            return returnFormat(Yii::$app->params['errorCode']['success']);
        } else {
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    public function removeGroupUser($arrInput)
    {
        $intUserId = $arrInput['user_id'];
        $intApplyUserId = $arrInput['apply_user_id'];
        $intGroupId = $arrInput['group_id'];
        if (empty($intApplyUserId) || empty($intUserId) || empty($intGroupId)|| self::_checkUserIsGroupAdmin($intUserId,$intGroupId)) {
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $objGroupUser = GroupUser::find()->where('user_id=:user_id and group_id=:group_id',[':user_id'=>$intApplyUserId,':group_id'=>$intGroupId])->one();
        if ($objGroupUser->delete()) {
            return returnFormat(Yii::$app->params['errorCode']['success']);
        } else {
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }


    public static function _checkUserIsGroupAdmin($intUserId,$intGroupId){
        $arrData = self::find()->select('group_admin_id')->where('group_id=:group_id',[':group_id'=>$intGroupId])->asArray()->one();
        if ($arrData['group_admin_id'] == $intUserId){
            return true;
        }else{
            return false;
        }
    }

    public static function _formatGroupInfo($arrGroups)
    {
        foreach ($arrGroups as &$item) {
            if (!empty($item['group_admin_id'])) {
                $intAdminId = $item['group_admin_id'];
                $arrUserName = User::find()->select('user_name')->asArray()->where('user_id=:user_id', [':user_id' => $intAdminId])->one();
                $strAdminUserName = $arrUserName['user_name'];
                $item['admin_user_name'] = $strAdminUserName;
            }

            $item['avatar'] = 'temp';

            if (!empty($item['organization_id'])) {
                $intOrganizationId = $item['organization_id'];
                $item['organization'] = Organization::_getOrganizationByIds(array($intOrganizationId))[$intOrganizationId];
            }

        }
        return $arrGroups;
    }

    /**
     * 前端展示用标签名
     */
    public function attributeLabels()
    {
        return [
            'group_name' => '课题组',
            'description' => '简介',
        ];
    }

    public static function _getGroupName($arrGroupId)
    {
        $arrInfo = self::find()->select(['group_name', 'group_id'])->indexBy('group_id')->asArray()->where(['group_id' => $arrGroupId])->all();
        return $arrInfo;
    }


}
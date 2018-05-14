<?php
namespace app\controllers;
use app\models\Group;
use app\models\GroupUser;
use app\models\User;
use app\models\Message;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class GroupController extends \yii\web\Controller
{
    public function actionUpdate()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        if (!$bolIsLogin){
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $intUserId =  Yii::$app->session['user']['user_id'];
        $objGroupModel = new Group();
        if (Yii::$app->request->isPost){
            $arrPost = Yii::$app->request->post();
            $arrPost['update_user_id'] = $intUserId;
            $arrOutput = $objGroupModel->updateGroup($arrPost);
            if($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success'])
            {
                Yii::$app->session->setFlash('info', '更新成功');
            }else{
                Yii::$app->session->setFlash('info', '更新失败');
            }
            $this->redirect(['lims/group']);
            Yii::$app->end();
        }
    }

    public function actionAdd()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        if (!$bolIsLogin){
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $intUserId =  Yii::$app->session['user']['user_id'];

        $objGroupModel = new Group();
        if (Yii::$app->request->isPost){
            $arrPost = Yii::$app->request->post();
            $arrPost['Group']['group_admin_id'] = $intUserId;
            $arrOutput = $objGroupModel->addGroup($arrPost);
            if($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success'])
            {
                Yii::$app->session->setFlash('info', '创建成功');
            }else{
                Yii::$app->session->setFlash('info', '创建失败');
            }
            $this->redirect(['lims/group']);
            Yii::$app->end();
        }
    }

    public function actionJoin()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        if (!$bolIsLogin){
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $arrUserData = self::_getLoginUserInfo();
        $intUserId =  Yii::$app->session['user']['user_id'];
        $intUserType = intval($arrUserData['user_type']);
        $intGroupId =  Yii::$app->request->get('group_id',0);
        if ($intUserType == User::USER_TYPE_GROUP_ADMIN){
            Yii::$app->session->setFlash('info', '课题组负责人不能加入其他课题组');
            $this->redirect(['lims/group']);
            Yii::$app->end();
        }
        if (empty($intGroupId)){
            Yii::$app->session->setFlash('info', '加入失败');
            $this->goBack(Yii::$app->request->getReferrer());
        }
        $arrGroupInfo = Group::find()->where(['group_id'=>$intGroupId])->asArray()->one();
        $intGroupAdminId = $arrGroupInfo['group_admin_id'];

        $objGroup = new Group();
        $arrInput = array(
            'user_id' => $intUserId,
            'group_id' => $intGroupId,
        );
        $arrOutput = $objGroup->joinGroup($arrInput);
        if($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success'])
        {
            Yii::$app->session->setFlash('info', '申请成功');
            // 发送消息
            $arrInput = array(
                'user_id' => $intGroupAdminId,
                'content' => Yii::$app->params['message_content']['join_group_apply'],
            );
            $objMessage = new Message();
            $objMessage->addMessage($arrInput);
        }else{
            Yii::$app->session->setFlash('info', '申请失败');
        }
        $this->redirect(['lims/group']);
        Yii::$app->end();
    }


    public function actionAgreeApply()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        if (!$bolIsLogin){
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $intUserId =  Yii::$app->session['user']['user_id'];
        $intApplyUserId = Yii::$app->request->get('user_id',0);
        $intGroupId = Yii::$app->request->get('group_id',0);
        if (empty($intApplyUserId)){
            $this->goBack(Yii::$app->request->getReferrer());
        }
        $objGroupModel = new Group();
        $arrInput = array(
            'user_id' => $intUserId,
            'apply_user_id' => $intApplyUserId,
            'group_id' => $intGroupId,
        );
        $arrOutput = $objGroupModel->agreeApply($arrInput);
        if($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success'])
        {
            Yii::$app->session->setFlash('info', '操作成功');
            // 发送消息
            $arrInput = array(
                'user_id' => $intApplyUserId,
                'content' => Yii::$app->params['message_content']['agree_join_group'],
            );
            $objMessage = new Message();
            $objMessage->addMessage($arrInput);
        }else{
            Yii::$app->session->setFlash('info', '操作成功');
        }
        $this->goBack(Yii::$app->request->getReferrer());
    }

    public function actionDisagreeApply()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        if (!$bolIsLogin){
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $intUserId =  Yii::$app->session['user']['user_id'];
        $intApplyUserId = Yii::$app->request->get('user_id',0);
        $intGroupId = Yii::$app->request->get('group_id',0);
        if (empty($intApplyUserId)){
            $this->goBack(Yii::$app->request->getReferrer());
        }
        $objGroupModel = new Group();
        $arrInput = array(
            'user_id' => $intUserId,
            'apply_user_id' => $intApplyUserId,
            'group_id' => $intGroupId,
        );
        $arrOutput = $objGroupModel->disagreeApply($arrInput);
        if($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success'])
        {
            Yii::$app->session->setFlash('info', '操作成功');
            // 发送消息
            $arrInput = array(
                'user_id' => $intApplyUserId,
                'content' => Yii::$app->params['message_content']['disagree_join_group'],
            );
            $objMessage = new Message();
            $objMessage->addMessage($arrInput);
        }else{
            Yii::$app->session->setFlash('info', '操作成功');
        }
        $this->goBack(Yii::$app->request->getReferrer());
    }

    public function actionRemoveGroupUser()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        if (!$bolIsLogin){
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $intUserId =  Yii::$app->session['user']['user_id'];
        $intApplyUserId = Yii::$app->request->get('user_id',0);
        $intGroupId = Yii::$app->request->get('group_id',0);
        if (empty($intApplyUserId)){
            $this->goBack(Yii::$app->request->getReferrer());
        }
        $objGroupModel = new Group();
        $arrInput = array(
            'user_id' => $intUserId,
            'apply_user_id' => $intApplyUserId,
            'group_id' => $intGroupId,
        );
        $arrOutput = $objGroupModel->removeGroupUser($arrInput);
        if($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success'])
        {
            Yii::$app->session->setFlash('info', '操作成功');
        }else{
            Yii::$app->session->setFlash('info', '操作成功');
        }
        $this->goBack(Yii::$app->request->getReferrer());
    }



    private function _getLoginUserInfo()
    {
        $bolIsLogin = isset(Yii::$app->session['user']['isLogin']);
        $intUserId = Yii::$app->session['user']['user_id'];
        if(!$bolIsLogin || empty($intUserId)){
            return false;
        }
        $arrUserIds = array($intUserId);
        $arrUsersInfo = User::_getUserAllInfo($arrUserIds);
        return $arrUsersInfo[$intUserId];
    }

}
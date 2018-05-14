<?php
namespace app\controllers;

use app\models\User;
use app\models\UserBlacklist;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\FollowInstrument;

class UserController extends \yii\web\Controller
{
    public function actionFollow()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        $intLoginUserId = Yii::$app->session['user']['user_id'];
        if (!$bolIsLogin || empty($intLoginUserId)){
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $intInstrumentId = intval(Yii::$app->request->get('instrument_id',0));
        if (empty($intInstrumentId)){
            $this->goBack();
            Yii::$app->end();
        }
        $arrInput = array(
            'user_id' => $intLoginUserId,
            'instrument_id' => $intInstrumentId,
        );
        $objFollowModel = new FollowInstrument();

        $arrOutput = $objFollowModel->addFollow($arrInput);
        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            Yii::$app->session->setFlash('info', '关注成功');
        }else{
            Yii::$app->session->setFlash('info', '关注失败');
        }
        $this->goBack(Yii::$app->request->getReferrer());
    }

    public function actionUnfollow()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        $intLoginUserId = Yii::$app->session['user']['user_id'];
        if (!$bolIsLogin || empty($intLoginUserId)){
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $intInstrumentId = intval(Yii::$app->request->get('instrument_id',0));
        if (empty($intInstrumentId)){
            $this->goBack();
            Yii::$app->end();
        }
        $arrInput = array(
            'user_id' => $intLoginUserId,
            'instrument_id' => $intInstrumentId,
        );
        $objFollowModel = new FollowInstrument();
        $arrOutput = $objFollowModel->unfollow($arrInput);
        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            Yii::$app->session->setFlash('info', '取消关注成功');
        }else{
            Yii::$app->session->setFlash('info', '取消关注失败');
        }
        $this->goBack(Yii::$app->request->getReferrer());

    }

    public function actionUpdate()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        if (!$bolIsLogin){
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $intUserId =  Yii::$app->session['user']['user_id'];

        $objUserModel = new User();
        if (Yii::$app->request->isPost){
            $arrPost = Yii::$app->request->post();
            $arrPost['User']['user_id'] = $intUserId;
            if($objUserModel->updateInfo($arrPost))
            {
                Yii::$app->session->setFlash('info', '修改成功');
            }else{
                Yii::$app->session->setFlash('info', '修改失败');
            }
            $this->goBack(Yii::$app->request->getReferrer());

            Yii::$app->end();
        }
    }

    public function actionAddBlacklist()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        if (!$bolIsLogin){
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $intLoginUserId =  intval(Yii::$app->session['user']['user_id']);
        $intUserId = intval(Yii::$app->request->get('user_id',0));

        if (empty($intUserId) || empty($intLoginUserId) || $intUserId == $intLoginUserId){
            Yii::$app->session->setFlash('info', '异常操作');
            return $this->goBack(Yii::$app->request->getReferrer());
        }
        $arrInfo = User::find()->asArray()->where(['user_id'=>$intLoginUserId])->one();
        $intUserType = $arrInfo['user_type'];
        if ($intUserType != User::USER_TYPE_INSTRUMENT_ADMIN){
            Yii::$app->session->setFlash('info', '对不起，您没有该权限');
            return $this->goBack(Yii::$app->request->getReferrer());
        }
        $arrInput = array(
            'admin_user_id' => $intLoginUserId,
            'user_id' => $intUserId,
            'blacklist_type' => UserBlacklist::BLACKLIST_TYPE_ADMIN,
            'instrument_id' => 0,
        );
        $objBlacklist = new UserBlacklist();
        $arrOutput = $objBlacklist->addBlacklist($arrInput);
        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            Yii::$app->session->setFlash('info', '加入黑名单成功');
        }else{
            Yii::$app->session->setFlash('info', '加入黑名单失败');
        }
        return $this->goBack(Yii::$app->request->getReferrer());
    }


    public function actionRemoveBlacklist()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        if (!$bolIsLogin){
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $intLoginUserId =  intval(Yii::$app->session['user']['user_id']);
        $intUserId = intval(Yii::$app->request->get('user_id',0));

        if (empty($intUserId) || empty($intLoginUserId)){
            Yii::$app->session->setFlash('info', '异常操作');
            return $this->goBack(Yii::$app->request->getReferrer());
        }

        $arrInfo = User::find()->asArray()->where(['user_id'=>$intLoginUserId])->one();
        $intUserType = $arrInfo['user_type'];
        if ($intUserType != User::USER_TYPE_INSTRUMENT_ADMIN){
            Yii::$app->session->setFlash('info', '对不起，您没有该权限');
            return $this->goBack(Yii::$app->request->getReferrer());
        }

        $arrInput = array(
            'admin_user_id' => $intLoginUserId,
            'user_id' => $intUserId,
        );
        $objBlacklist = new UserBlacklist();
        $arrOutput = $objBlacklist->removeBlacklist($arrInput);
        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            Yii::$app->session->setFlash('info', '移出黑名单成功');
        }else{
            Yii::$app->session->setFlash('info', '移出黑名单失败');
        }
        return $this->goBack(Yii::$app->request->getReferrer());
    }


}
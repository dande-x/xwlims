<?php
namespace app\controllers;
use app\models\Instrument;
use app\models\InstrumentAdmin;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class InstrumentController extends \yii\web\Controller
{

    public function actionAdd()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        if (!$bolIsLogin){
            return $this->redirect(['lims/login']);
        }
        $intLoginUserId =  Yii::$app->session['user']['user_id'];

        $arrInfo = User::find()->asArray()->where(['user_id'=>$intLoginUserId])->one();
        $intUserType = $arrInfo['user_type'];
        if ($intUserType != User::USER_TYPE_SYSTEM_ADMIN){
            Yii::$app->session->setFlash('info', '对不起，您没有该权限');
            return $this->goBack(Yii::$app->request->getReferrer());
        }

        $file = $_FILES['instrument_image'];
        if (isset($file)){
            if($file['error']>0){
                $strError = '上传失败';
                switch('error'){
                    case 1:
                        $strError.='大小超过了服务器设置的限制！';
                        break;
                    case 2:
                        $strError.='文件大小超过了表单设置的限制！';
                        break;
                    case 3:
                        $strError.='文件只有部分被上传';
                        break;
                    case 4:
                        $strError.='没有文件被上传';
                        break;
                    case 6:
                        $strError.='上传文件的临时目录不存在！';
                        break;
                    case 7:
                        $strError.='写入失败';
                        break;
                    default:
                        $strError.='未知错误';
                        break;
                }
                Yii::$app->session->setFlash('info', $strError);
                return $this->goBack(Yii::$app->request->getReferrer());
            }
        }

        $objInstrumentModel = new Instrument();
        $arrPost = Yii::$app->request->post();
        $arrPost['img'] = $file['tmp_name'];
        if($objInstrumentModel->add($arrPost)['error_code'] ==  Yii::$app->params['errorCode']['success'])
        {
            Yii::$app->session->setFlash('info', '创建成功');

        }else{
            Yii::$app->session->setFlash('info', '创建失败');

        }


        return $this->goBack(Yii::$app->request->getReferrer());
    }

    public function actionUpdate()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        if (!$bolIsLogin){
            return $this->redirect(['lims/login']);
        }
        $intLoginUserId =  Yii::$app->session['user']['user_id'];
        $arrInfo = User::find()->asArray()->where(['user_id'=>$intLoginUserId])->one();
        $intUserType = $arrInfo['user_type'];
        if ($intUserType != User::USER_TYPE_SYSTEM_ADMIN && $intUserType != User::USER_TYPE_INSTRUMENT_ADMIN){
            Yii::$app->session->setFlash('info', '对不起，您没有该权限');
            return $this->goBack(Yii::$app->request->getReferrer());
        }
        $file = $_FILES['instrument_image'];
        if (isset($file)){
            if($file['error']>0){
                $strError = '上传失败';
                switch('error'){
                    case 1:
                        $strError.='大小超过了服务器设置的限制！';
                        break;
                    case 2:
                        $strError.='文件大小超过了表单设置的限制！';
                        break;
                    case 3:
                        $strError.='文件只有部分被上传';
                        break;
                    case 4:
                        $strError.='没有文件被上传';
                        break;
                    case 6:
                        $strError.='上传文件的临时目录不存在！';
                        break;
                    case 7:
                        $strError.='写入失败';
                        break;
                    default:
                        $strError.='未知错误';
                        break;
                }
                Yii::$app->session->setFlash('info', $strError);
                return $this->goBack(Yii::$app->request->getReferrer());
            }
        }

        $objInstrumentModel = new Instrument();
        $arrPost = Yii::$app->request->post();
        $arrPost['img'] = $file['tmp_name'];

        if($objInstrumentModel->updateInfo($arrPost)['error_code'] ==  Yii::$app->params['errorCode']['success'])
        {
            Yii::$app->session->setFlash('info', '修改成功');

        }else{
            Yii::$app->session->setFlash('info', '修改失败');

        }
        return $this->goBack(Yii::$app->request->getReferrer());
    }

    public function actionSetInstrumentAdmin()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        if (!$bolIsLogin){
            return $this->redirect(['lims/login']);
        }
        $intLoginUserId =  Yii::$app->session['user']['user_id'];
        $arrInfo = User::find()->asArray()->where(['user_id'=>$intLoginUserId])->one();
        $intUserType = $arrInfo['user_type'];
        if ($intUserType != User::USER_TYPE_SYSTEM_ADMIN){
            Yii::$app->session->setFlash('info', '对不起，您没有该权限');
            return $this->goBack(Yii::$app->request->getReferrer());
        }

        $intAdminId = intval(Yii::$app->request->get('admin_user_id',0));
        $intInstId = intval(Yii::$app->request->get('instrument_id',0));

        $arrInput = array(
            'admin_user_id' => $intAdminId,
            'instrument_id' => $intInstId,
        );
        $objInstAdmin = new InstrumentAdmin();
        $arrOutput = $objInstAdmin->setInstrumentAdmin($arrInput);
        if($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success'])
        {
            Yii::$app->session->setFlash('info', '设置成功');

        }else{
            Yii::$app->session->setFlash('info', '设置失败');
        }
        return $this->goBack(Yii::$app->request->getReferrer());

    }



}
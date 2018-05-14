<?php
namespace app\controllers;

use app\models\Appointment;
use app\models\GroupUser;
use app\models\Instrument;
use app\models\InstrumentAdmin;
use app\models\Message;
use app\models\User;
use app\models\UserBlacklist;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class AppointmentController extends \yii\web\Controller
{
    public function actionApply()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        $intLoginUserId = Yii::$app->session['user']['user_id'];
        if (!$bolIsLogin || empty($intLoginUserId)) {
            return $this->redirect(['lims/login']);
        }

        $intInstrumentId = intval(Yii::$app->request->post('instrument_id',0));
        $strTheme = strval(Yii::$app->request->post('theme',0));
        $strComment = strval(Yii::$app->request->post('appointment_comment',0));
        $strDaterange = strval(Yii::$app->request->post('daterange',0));
        $intGroupId = GroupUser::_getUserJoinGroupId($intLoginUserId);

        // 查找管理员id
        $objInstAdminModel = new InstrumentAdmin();
        $arrAdminIdOutput = $objInstAdminModel->getInstrumentAdminByInst(array($intInstrumentId));
        $arrAdminId = $arrAdminIdOutput['data'];
        $intAdminUserId = intval($arrAdminId[$intInstrumentId]['user_id']);

        if (empty($intGroupId)){
            Yii::$app->session->setFlash('info', '必须加入课题组才能预约');
            return $this->goBack(Yii::$app->request->getReferrer());
        }

        $arrDate = explode(" - ",$strDaterange);
        $intStartTime = strtotime($arrDate[0]);
        $intEndTime = strtotime($arrDate[1]);
        if (empty($intInstrumentId) || empty($intStartTime) || empty($intEndTime) || $intEndTime <= $intStartTime){
            Yii::$app->session->setFlash('info', '预约失败，请检查输入数据。');
            return $this->goBack(Yii::$app->request->getReferrer());
        }

        // 仪器状态不正常的不能预约
        $arrInstInfo = Instrument::find()->where(['instrument_id'=>$intInstrumentId])->asArray()->one();
        $intInstStatus = intval($arrInstInfo['status']);
        if ($intInstStatus != Instrument::INSTRUMENT_STATUS_NORMAL){
            Yii::$app->session->setFlash('info', '预约失败，仪器目前暂不可预约。');
            return $this->goBack(Yii::$app->request->getReferrer());
        }


        // 还有预约没有完成的不能再次预约
        $arrAppointmentEventFind = Appointment::find()->where(['user_id' => $intLoginUserId,
                'status' => array(Appointment::APPOINTMENT_STATUS_INIT,Appointment::APPOINTMENT_STATUS_AGREE,)])->asArray()->all();
        if (!empty($arrAppointmentEventFind)){
            Yii::$app->session->setFlash('info', '还有预约没有完成的不能再次预约');
            return $this->goBack(Yii::$app->request->getReferrer());
        }

        // 在黑名单的不能预约
        $bolBlock = UserBlacklist::_checkBlacklist($intLoginUserId,$intAdminUserId,$intInstrumentId);
        if (!$bolBlock){
            Yii::$app->session->setFlash('info', '因为您的不恰当操作，已被管理员拉黑，暂不能预约，请联系该仪器管理员。');
            return $this->goBack(Yii::$app->request->getReferrer());
        }

        // 在其他预约时间段内 不能预约
        $bolTime = Appointment::_checkTime($intStartTime,$intEndTime);
        if (!$bolTime){
            Yii::$app->session->setFlash('info', '您选择的该时间段和其他预约冲突，请参考下表选择合适的时间。');
            return $this->goBack(Yii::$app->request->getReferrer());
        }


        $arrInput = array(
            'instrument_id' => $intInstrumentId,
            'theme' => $strTheme,
            'user_id' => $intLoginUserId,
            'group_id' => $intGroupId,
            'start_time' => $intStartTime,
            'end_time' => $intEndTime,
            'appointment_comment' => $strComment,
            'admin_user_id' => $intAdminUserId,
        );

        $objAppointmentModel = new Appointment();
        $arrOutput = $objAppointmentModel->apply($arrInput);
        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            Yii::$app->session->setFlash('info', '预约成功');
            // 发送消息
            $arrInput = array(
                'user_id' => $intAdminUserId,
                'content' => Yii::$app->params['message_content']['appointment_for_admin'],
            );
            $objMessage = new Message();
            $objMessage->addMessage($arrInput);
        }else{
            Yii::$app->session->setFlash('info', '预约失败');
        }
        $this->goBack(Yii::$app->request->getReferrer());
    }

    public function actionGetappointment()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        $intLoginUserId = Yii::$app->session['user']['user_id'];
        if (!$bolIsLogin || empty($intLoginUserId)) {
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $this->layout = false;
        $intInstrumentId = intval(Yii::$app->request->get('instrument_id',0));
        if (empty($intInstrumentId)){
            return false;
        }
        $objAppointmentEventFind = Appointment::find()->where(['instrument_id' => $intInstrumentId,
                'status' => array(Appointment::APPOINTMENT_STATUS_INIT,Appointment::APPOINTMENT_STATUS_AGREE)]);
        $arrAppointmentEvent = $objAppointmentEventFind->asArray()->all();
        $arrAppointmentEvent = Appointment::_formatAppointment($arrAppointmentEvent);
        $arrAppointmentEvent = Appointment::_formatAppointmentToEvent($arrAppointmentEvent);

        echo json_encode($arrAppointmentEvent);
        return;
    }

    public function actionAgree()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        $intLoginUserId = Yii::$app->session['user']['user_id'];
        if (!$bolIsLogin || empty($intLoginUserId)) {
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $this->layout = false;
        $intAppointmentId = intval(Yii::$app->request->get('appointment_id',0));
        if (empty($intAppointmentId)){
            return $this->render('@app/views/lims/error.php',['message'=>'']);
        }
        $arrAppointmentInfo = Appointment::find()->where('appointment_id=:appointment_id',[':appointment_id'=>$intAppointmentId])->asArray()->one();
        $intInstrumentId = $arrAppointmentInfo['instrument_id'];
        $intAppointmentUserId = $arrAppointmentInfo['user_id'];
        if (empty($intInstrumentId) || $arrAppointmentInfo['status'] != Appointment::APPOINTMENT_STATUS_INIT){
            return $this->render('@app/views/lims/error.php',['message'=>'']);
        }

        $objInstrumentAdmin = new InstrumentAdmin();
        $arrOutput = $objInstrumentAdmin->getInstrumentAdminByInst(array($intInstrumentId));
        if ($arrOutput['error_code'] == Yii::$app->params['errorCode']['success'] || !empty($arrOutput['data'])){
            $arrAdmin = $arrOutput['data'];
            $intAdminId = $arrAdmin[$intInstrumentId]['user_id'];
            if ($intAdminId != $intLoginUserId){
                Yii::$app->session->setFlash('info', '对不起，非该仪器管理员无法进行此操作');
                $this->goBack(Yii::$app->request->getReferrer());
            }
        }else{
            return $this->render('@app/views/lims/error.php',['message'=>'']);
        }
        $objAppointment = new Appointment();
        $arrInput = array(
            'appointment_id' => $intAppointmentId,
            'status' => Appointment::APPOINTMENT_STATUS_AGREE,
        );
        $arrOutput = $objAppointment->setAppointmentStatus($arrInput);

        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            Yii::$app->session->setFlash('info', '操作成功');
            // 发送消息
            $arrInput = array(
                'user_id' => $intAppointmentUserId,
                'content' => Yii::$app->params['message_content']['admin_agree_appointment'],
            );
            $objMessage = new Message();
            $objMessage->addMessage($arrInput);
        }else{
            Yii::$app->session->setFlash('info', '操作成功');
        }
        $this->goBack(Yii::$app->request->getReferrer());
    }

    public function actionDisagree()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        $intLoginUserId = Yii::$app->session['user']['user_id'];
        if (!$bolIsLogin || empty($intLoginUserId)) {
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        $this->layout = false;
        $intAppointmentId = intval(Yii::$app->request->get('appointment_id',0));
        if (empty($intAppointmentId)){
            return $this->render('@app/views/lims/error.php',['message'=>'']);
        }
        $arrAppointmentInfo = Appointment::find()->where('appointment_id=:appointment_id',[':appointment_id'=>$intAppointmentId])->asArray()->one();
        $intInstrumentId = $arrAppointmentInfo['instrument_id'];
        $intAppointmentUserId = $arrAppointmentInfo['user_id'];

        if (empty($intInstrumentId) || $arrAppointmentInfo['status'] != Appointment::APPOINTMENT_STATUS_INIT){
            return $this->render('@app/views/lims/error.php',['message'=>'']);
        }

        $objInstrumentAdmin = new InstrumentAdmin();
        $arrOutput = $objInstrumentAdmin->getInstrumentAdminByInst(array($intInstrumentId));
        if ($arrOutput['error_code'] == Yii::$app->params['errorCode']['success'] || !empty($arrOutput['data'])){
            $arrAdmin = $arrOutput['data'];
            $intAdminId = $arrAdmin[$intInstrumentId]['user_id'];
            if ($intAdminId != $intLoginUserId){
                Yii::$app->session->setFlash('info', '对不起，非该仪器管理员无法进行此操作');
                $this->goBack(Yii::$app->request->getReferrer());
            }
        }else{
            return $this->render('@app/views/lims/error.php',['message'=>'']);

        }
        $objAppointment = new Appointment();
        $arrInput = array(
            'appointment_id' => $intAppointmentId,
            'status' => Appointment::APPOINTMENT_STATUS_DISAGREE,
        );
        $arrOutput = $objAppointment->setAppointmentStatus($arrInput);

        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            Yii::$app->session->setFlash('info', '操作成功');
            // 发送消息
            $arrInput = array(
                'user_id' => $intAppointmentUserId,
                'content' => Yii::$app->params['message_content']['admin_disagree_appointment'],
            );
            $objMessage = new Message();
            $objMessage->addMessage($arrInput);
        }else{
            Yii::$app->session->setFlash('info', '操作成功');
        }
        $this->goBack(Yii::$app->request->getReferrer());
    }


    public function actionUsed()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        $intLoginUserId = Yii::$app->session['user']['user_id'];
        if (!$bolIsLogin || empty($intLoginUserId)) {
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        // $this->layout = false;
        $intAppointmentId = intval(Yii::$app->request->get('appointment_id',0));
        $strFeedback = strval(Yii::$app->request->get('feedback',''));
        if (empty($intAppointmentId) || '' == $strFeedback){
            return $this->render('@app/views/lims/error.php',['message'=>'']);
        }

        $arrAppointmentInfo = Appointment::find()->where('appointment_id=:appointment_id',[':appointment_id'=>$intAppointmentId])->asArray()->one();
        $intUserId = $arrAppointmentInfo['user_id'];
        if (empty($intUserId) || $intUserId != $intLoginUserId || $arrAppointmentInfo['status'] != Appointment::APPOINTMENT_STATUS_AGREE){
            return $this->render('error',['message'=>'']);
        }

        $objAppointment = new Appointment();
        $arrInput = array(
            'appointment_id' => $intAppointmentId,
            'appointment_feedback' => $strFeedback,
        );
        $arrOutput = $objAppointment->setAppointmentUsed($arrInput);

        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            Yii::$app->session->setFlash('info', '操作成功');
        }else{
            Yii::$app->session->setFlash('info', '操作成功');
        }
        $this->goBack(Yii::$app->request->getReferrer());
    }


    public function actionCancel()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        $intLoginUserId = Yii::$app->session['user']['user_id'];
        if (!$bolIsLogin || empty($intLoginUserId)) {
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        // $this->layout = false;
        $intAppointmentId = intval(Yii::$app->request->get('appointment_id',0));
        if (empty($intAppointmentId)){
            return $this->render('@app/views/lims/error.php',['message'=>'']);
        }

        $arrAppointmentInfo = Appointment::find()->where('appointment_id=:appointment_id',[':appointment_id'=>$intAppointmentId])->asArray()->one();
        $intUserId = $arrAppointmentInfo['user_id'];
        if (empty($intUserId) || $intUserId != $intLoginUserId){
            return $this->render('error',['message'=>'']);
        }

        $objAppointment = new Appointment();
        $arrInput = array(
            'appointment_id' => $intAppointmentId,
            'status' => Appointment::APPOINTMENT_STATUS_CANCEL,
        );
        $arrOutput = $objAppointment->setAppointmentStatus($arrInput);

        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            Yii::$app->session->setFlash('info', '操作成功');
        }else{
            Yii::$app->session->setFlash('info', '操作成功');
        }
        $this->goBack(Yii::$app->request->getReferrer());
    }


    public function actionDone()
    {
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        $intLoginUserId = Yii::$app->session['user']['user_id'];
        if (!$bolIsLogin || empty($intLoginUserId)) {
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }
        // $this->layout = false;
        $intAppointmentId = intval(Yii::$app->request->get('appointment_id',0));
        if (empty($intAppointmentId)){
            return $this->render('@app/views/lims/error.php',['message'=>'']);
        }

        $arrAppointmentInfo = Appointment::find()->where('appointment_id=:appointment_id',[':appointment_id'=>$intAppointmentId])->asArray()->one();
        $intInstrumentId = $arrAppointmentInfo['instrument_id'];
        if (empty($intInstrumentId)){
            return $this->render('error',['message'=>'']);
        }

        $objInstrumentAdmin = new InstrumentAdmin();
        $arrOutput = $objInstrumentAdmin->getInstrumentAdminByInst(array($intInstrumentId));
        if ($arrOutput['error_code'] == Yii::$app->params['errorCode']['success'] || !empty($arrOutput['data'])){
            $arrAdmin = $arrOutput['data'];
            $intAdminId = $arrAdmin[$intInstrumentId]['user_id'];
            if ($intAdminId != $intLoginUserId){
                Yii::$app->session->setFlash('info', '对不起，非该仪器管理员无法进行此操作');
                $this->goBack(Yii::$app->request->getReferrer());
            }
        }else{
            return $this->render('@app/views/lims/error.php',['message'=>'']);
        }

        $objAppointment = new Appointment();
        $arrInput = array(
            'appointment_id' => $intAppointmentId,
            'status' => Appointment::APPOINTMENT_STATUS_DONE,
        );
        $arrOutput = $objAppointment->setAppointmentStatus($arrInput);

        if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
            Yii::$app->session->setFlash('info', '操作成功');
        }else{
            Yii::$app->session->setFlash('info', '操作成功');
        }
        $this->goBack(Yii::$app->request->getReferrer());
    }


}
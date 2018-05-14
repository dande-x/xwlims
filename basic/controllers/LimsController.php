<?php

namespace app\controllers;

use app\models\FollowInstrument;
use app\models\Instrument;
use app\models\InstrumentAdmin;
use app\models\Message;
use app\models\Organization;
use app\models\User;
use app\models\Group;
use app\models\GroupUser;
use app\models\Appointment;

use app\models\UserBlacklist;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\data\Pagination;

class LimsController extends \yii\web\Controller
{
    public $layout = 'limsLayout';
    const SEARCH_GROUP_USER_STATUS_ALL = 1;
    const SEARCH_GROUP_USER_STATUS_INIT = 2;
    const SEARCH_GROUP_USER_STATUS_JOIN = 3;

    public function actionIndex()
    {
        return false;
    }

    public function actionHomepage()
    {
        $arrUserInfo = $this->_getLoginUserInfo();
        if(false != $arrUserInfo){
            $arrUserInfo = User::_formatUserInfo(array($arrUserInfo))[0];
        }
        if ($arrUserInfo){
            $this->view->params['isLogin'] = true;
            $this->view->params['login_user_info'] = $arrUserInfo;
        }else{
            $this->view->params['isLogin'] = false;
            $this->view->params['login_user_info'] = false;
        }
        $intLoginUserId = Yii::$app->session['user']['user_id'];
        $objUserModel = new User();

        // 关注仪器
        $objFollowModel = FollowInstrument::find();
        $intFollowCount = $objFollowModel->where('user_id=:user_id',[':user_id'=>$intLoginUserId])->count();
        $objFollowPage = new Pagination(['totalCount' => $intFollowCount,'pageSize' => Yii::$app->params['pageSize']['follow_instrument']]);
        $arrFollowData = $objFollowModel->where('user_id=:user_id',[':user_id'=>$intLoginUserId])->offset($objFollowPage->offset)->limit($objFollowPage->limit)->asArray()->all();
        $arrFollowInstrumentId = array();
        foreach ($arrFollowData as $item)
        {
            $arrFollowInstrumentId[] = intval($item['instrument_id']);
        }

        // 格式化
        $arrInstrumentOutput = Instrument::_getInstrumentInfoById($arrFollowInstrumentId);
        $arrInstrumentInfo = $arrInstrumentOutput['data'];
        $arrInstrumentInfo = Instrument::_formatInstrumentInfo($arrInstrumentInfo);
        $arrInstrumentInfo = Instrument::_getInstrumentAdminInfo($arrInstrumentInfo);

        return $this->render('homepage',[
            'user_model' => $objUserModel,
            'arrFollowInstrument' => $arrInstrumentInfo,
            'objFollowInstrumentPage' => $objFollowPage,
        ]);
    }

    public function actionAppointment()
    {
        $arrUserInfo = $this->_getLoginUserInfo();
        if ($arrUserInfo){
            $this->view->params['isLogin'] = true;
            $this->view->params['login_user_info'] = $arrUserInfo;
        }else{
            $this->view->params['isLogin'] = false;
        }
        $intLoginUserId = Yii::$app->session['user']['user_id'];
        $intUserType = $arrUserInfo['user_type'];

        // 当前预约记录
        $arrCurrentAppointment = Appointment::find()->where(
            ['user_id' => $intLoginUserId,'status'=> array(Appointment::APPOINTMENT_STATUS_INIT,Appointment::APPOINTMENT_STATUS_AGREE,Appointment::APPOINTMENT_STATUS_DISAGREE,Appointment::APPOINTMENT_STATUS_USED) ])
            ->orderBy(['appointment_id' => SORT_DESC])->asArray()->one();
        if (!empty($arrCurrentAppointment)){
            $arrCurrentAppointment = Appointment::_formatAppointment(array($arrCurrentAppointment));
        }


        // 预约记录
        $objAppointmentHistoryFind = Appointment::find()->where('user_id=:user_id ' ,[':user_id' => $intLoginUserId,]);
        $intAppointmentHistoryCount = $objAppointmentHistoryFind->count();
        $objAppointmentHistoryPage = new Pagination(['totalCount' => $intAppointmentHistoryCount,'pageSize' => Yii::$app->params['pageSize']['appointment_history']
            ,'pageParam' => 'appointment_history_page','pageSizeParam'=>false]);
        $arrAppointmentHistory= $objAppointmentHistoryFind->offset($objAppointmentHistoryPage->offset)->limit($objAppointmentHistoryPage->limit)->asArray()->all();
        $arrAppointmentHistory = Appointment::_formatAppointment($arrAppointmentHistory);

        // 我管理的预约记录
        $arrAdminAppointment = array();
        $objAdminAppointmentPage = '';
        if ($intUserType == User::USER_TYPE_INSTRUMENT_ADMIN){
            $objAdminAppointmentFind = Appointment::find()->where('admin_user_id = :admin_user_id' ,[':admin_user_id' => $intLoginUserId ,]);
            $objAdminAppointmentPage = new Pagination(['totalCount' => $intAppointmentHistoryCount,'pageSize' => Yii::$app->params['pageSize']['appointment_history']
                ,'pageParam' => 'admin_appointment_page','pageSizeParam'=>false]);
            $arrAdminAppointment= $objAdminAppointmentFind->offset($objAppointmentHistoryPage->offset)->limit($objAppointmentHistoryPage->limit)->orderBy(['appointment_id' => SORT_DESC])->asArray()->all();
            $arrAdminAppointment = Appointment::_formatAppointment($arrAdminAppointment);
        }

        // 黑名单
        $arrBlacklist = array();
        if ($intUserType == User::USER_TYPE_INSTRUMENT_ADMIN){
            $arrBlacklistInfo = UserBlacklist::find()->where(['admin_user_id'=>$intLoginUserId])->asArray()->all();
            $arrBlacklist = UserBlacklist::_formatBlacklist($arrBlacklistInfo);
        }


        return $this->render('appointment',
            ['arrCurrentAppointment' => $arrCurrentAppointment,
                'arrAppointmentHistory' => $arrAppointmentHistory,
                'objAppointmentHistoryPage' => $objAppointmentHistoryPage,
                'arrAdminAppointment' => $arrAdminAppointment,
                'objAdminAppointmentPage' => $objAdminAppointmentPage,
                'arrBlacklist' => $arrBlacklist,
            ]
    );
    }

    public function actionGroup()
    {
        $arrUserInfo = $this->_getLoginUserInfo();
        if ($arrUserInfo){
            $this->view->params['isLogin'] = true;
            $this->view->params['login_user_info'] = $arrUserInfo;
        }else{
            $this->view->params['isLogin'] = false;
        }
        $intGroupId = $arrUserInfo['group_id'];
        $strSearchGroupName =  Yii::$app->request->get('search_group_name','');
        $intSearchGroupUserStatus =  intval(Yii::$app->request->get('search_group_user_status',1));
        // 1.全部  2 申请中 3 已加入

        $objGroupModel = new Group();

        if (!empty($strSearchGroupName)){
            // 搜索
            $objSearchGroup =  Group::find()->where("group_name like '%$strSearchGroupName%'");
            $intGroupsCount = $objSearchGroup->count();
            $objGroupsPage = new Pagination(['totalCount' => $intGroupsCount,'pageSize' => Yii::$app->params['pageSize']['groups'],'pageParam' => 'group_page','pageSizeParam'=>false]);
            $arrGroups = $objSearchGroup->offset($objGroupsPage->offset)->limit($objGroupsPage->limit)->asArray()->all();
        }else{
            $intGroupsCount = Group::find()->count();
            $objGroupsPage = new Pagination(['totalCount' => $intGroupsCount,'pageSize' => Yii::$app->params['pageSize']['groups'],'pageParam' => 'group_page','pageSizeParam'=>false]);
            // 课题组列表
            $arrGroups = Group::find()->offset($objGroupsPage->offset)->limit($objGroupsPage->limit)->asArray()->all();
        }

        // 我的课题组
        $arrGroupInfo =  Group::find()->where('group_id=:group_id',[':group_id'=>$intGroupId])->asArray()->one();
        $arrGroups = Group::_formatGroupInfo($arrGroups);
        $arrGroupInfo = Group::_formatGroupInfo(array($arrGroupInfo))[0];


        // 课题组用户列表
        $objGroupUserModel = GroupUser::find();
        if ($intSearchGroupUserStatus == self::SEARCH_GROUP_USER_STATUS_JOIN){
            $arrGroupUser = $objGroupUserModel->where('group_id=:group_id and status=:status' ,[':group_id' => $intGroupId,':status'=>GroupUser::STATUS_JOIN])->asArray()->all();
        }else if ($intSearchGroupUserStatus == self::SEARCH_GROUP_USER_STATUS_INIT){
            $arrGroupUser = $objGroupUserModel->where('group_id=:group_id and status=:status' ,[':group_id' => $intGroupId,':status'=>GroupUser::STATUS_INIT])->asArray()->all();
        }else{
            $arrGroupUser = $objGroupUserModel->where('group_id=:group_id' ,[':group_id' => $intGroupId])->asArray()->all();
        }
        $arrGroupUserId = array();
        foreach ($arrGroupUser as $item){
            $arrGroupUserId[] = $item['user_id'];
        }
        $arrGroupUserInfo = User::_getUserAllInfo($arrGroupUserId);
        foreach ($arrGroupUser as $key => $item){
            $arrGroupUser[$key] = array_merge($item,$arrGroupUserInfo[$item['user_id']]);
        }

        // 课题组预约记录
        $objAppointmentFind = Appointment::find()->where('group_id=:group_id' ,[':group_id' => $intGroupId]);
        $intAppointmentCount = $objAppointmentFind->count();
        $objAppointmentPage = new Pagination(['totalCount' => $intAppointmentCount,'pageSize' => Yii::$app->params['pageSize']['group_appointment'],'pageParam' => 'appointment_page','pageSizeParam'=>false]);
        $arrAppointment= $objAppointmentFind->offset($objAppointmentPage->offset)->limit($objAppointmentPage->limit)->asArray()->all();
        $arrAppointment = Appointment::_formatAppointment($arrAppointment);

        $arrOrganizationStructure = Organization::_getOrganizationStructure();
        $arrOrganizationMap = Organization::_getOrganizationMap();

        $arrGroupUser = User::_formatUserInfo($arrGroupUser);
        return $this->render('group',[
            'arrGroups'=>$arrGroups,
            'objGroupModel'=>$objGroupModel,
            'objPage'=>$objGroupsPage,
            'arrGroupInfo'=>$arrGroupInfo,
            'arrGroupUser' => $arrGroupUser,
            'arrAppointment' => $arrAppointment,
            'objAppointmentPage' => $objAppointmentPage,
            'arrOrganizationStructure' => $arrOrganizationStructure,
            'arrOrganizationMap' => $arrOrganizationMap,
        ]);
    }

    public function actionInstrument()
    {
        $arrUserInfo = $this->_getLoginUserInfo();
        if ($arrUserInfo){
            $this->view->params['isLogin'] = true;
            $this->view->params['login_user_info'] = $arrUserInfo;
        }else{
            $this->view->params['isLogin'] = false;
        }
        $strTab = 'instrument_list';
        $objAppointmentModel = new Appointment();
        $objInstrumentModel = new Instrument();
        $intUserType = $arrUserInfo['user_type'];

        $intLoginUserId = Yii::$app->session['user']['user_id'];
        $strSearchInstrumentName =  Yii::$app->request->get('search_instrument_name','');
        $bolSearchInstrumentAdmin =  (Yii::$app->request->get('search_instrument_admin',0) == 1) ? true : false;

        $arrOrganizationStructure = Organization::_getOrganizationStructure();
        $arrOrganizationMap = Organization::_getOrganizationMap();

        if ($bolSearchInstrumentAdmin){
            $arrOutput = InstrumentAdmin::getInstrumentByAdmin($intLoginUserId);
            if ($arrOutput['error_code'] == Yii::$app->params['errorCode']['success'] || !empty($arrOutput['data'])){
                $arrInstId = $arrOutput['data'];
                $objInstModelFind =  Instrument::find()->where(['instrument_id' => $arrInstId]);
                $intInstCount = $objInstModelFind->count();
                $objInstPage = new Pagination(['totalCount' => $intInstCount,'pageSize' => Yii::$app->params['pageSize']['instrument'],'pageSizeParam'=>false]);
                $arrInst = $objInstModelFind->offset($objInstPage->offset)->limit($objInstPage->limit)->asArray()->all();
            }else{
                return $this->render('error',['message'=>'']);
            }
        }
        else if (!empty($strSearchInstrumentName)){
            // 搜索
            $objInstModelFind =  Instrument::find()->where("instrument_name like '%$strSearchInstrumentName%'");
            $intInstCount = $objInstModelFind->count();
            $objInstPage = new Pagination(['totalCount' => $intInstCount,'pageSize' => Yii::$app->params['pageSize']['instrument'],'pageSizeParam'=>false]);
            $arrInst = $objInstModelFind->offset($objInstPage->offset)->limit($objInstPage->limit)->asArray()->all();
        }else{
            $intInstCount = Instrument::find()->count();
            $objInstPage = new Pagination(['totalCount' => $intInstCount,'pageSize' => Yii::$app->params['pageSize']['instrument'],'pageSizeParam'=>false]);
            $arrInst = Instrument::find()->offset($objInstPage->offset)->limit($objInstPage->limit)->asArray()->all();
        }


        $arrInst = Instrument::_formatInstrumentInfo($arrInst);
        $arrInst = Instrument::_getInstrumentFollowInfo($arrInst,$intLoginUserId);
        $arrInst = Instrument::_getInstrumentAdminInfo($arrInst);

        $intInstrumentId = intval(Yii::$app->request->get('instrument_id',0));

        // 仪器预约记录
        $objAppointmentFind = Appointment::find()->where('instrument_id=:instrument_id' ,[':instrument_id' => $intInstrumentId]);
        $intAppointmentCount = $objAppointmentFind->count();
        $objAppointmentPage = new Pagination(['totalCount' => $intAppointmentCount,
            'pageSize' => Yii::$app->params['pageSize']['instrument_appointment'],
            'pageParam' => 'appointment_page','pageSizeParam'=>false]);
        $arrAppointment= $objAppointmentFind->offset($objAppointmentPage->offset)->limit($objAppointmentPage->limit)->asArray()->all();
        $arrAppointment = Appointment::_formatAppointment($arrAppointment);

        $arrInstrumentInfo = array();
        if (!empty($intInstrumentId)){
            $arrOutput = Instrument::_getInstrumentInfoById(array($intInstrumentId));
            if ($arrOutput['error_code'] == Yii::$app->params['errorCode']['success']){
                $strTab = 'instrument_info';
                $arrInstrumentInfo = $arrOutput['data'];
                $arrInstrumentInfo = Instrument::_formatInstrumentInfo($arrInstrumentInfo);
                $arrInstrumentInfo = Instrument::_getInstrumentFollowInfo($arrInstrumentInfo,$intLoginUserId);
                $arrInstrumentInfo = Instrument::_getInstrumentAdminInfo($arrInstrumentInfo);
                $arrInstrumentInfo = $arrInstrumentInfo[0];
            }
        }

        // 技术员列表
        $arrAdminUser = array();
        if ($intUserType == User::USER_TYPE_SYSTEM_ADMIN || $intUserType == User::USER_TYPE_INSTRUMENT_ADMIN){
            $arrAdminUser = User::find()->where(['user_type' => User::USER_TYPE_INSTRUMENT_ADMIN])->asArray()->indexBy('user_id')->all();
        }
        return $this->render('instrument',[
            'arrInst' => $arrInst,
            'objInstPage' => $objInstPage,
            'strTab' => $strTab,
            'arrInstrumentInfo' => $arrInstrumentInfo,
            'objAppointmentModel' => $objAppointmentModel,
            'arrAppointment' => $arrAppointment,
            'objAppointmentPage' => $objAppointmentPage,
            'objInstrumentModel' => $objInstrumentModel,
            'arrOrganizationStructure' => $arrOrganizationStructure,
            'arrOrganizationMap' => $arrOrganizationMap,
            'arrAdminUser' => $arrAdminUser,
        ]);
    }

    public function actionMessage()
    {
        $arrUserInfo = $this->_getLoginUserInfo();
        if ($arrUserInfo){
            $this->view->params['isLogin'] = true;
            $this->view->params['login_user_info'] = $arrUserInfo;
        }else{
            $this->view->params['isLogin'] = false;
        }
        $intLoginUserId = Yii::$app->session['user']['user_id'];

        $objMessage =  Message::find()->where(['user_id' => $intLoginUserId]);
        $intCount = $objMessage->count();
        $objPage = new Pagination(['totalCount' => $intCount,'pageSize' => Yii::$app->params['pageSize']['message'],'pageParam' => 'group_page','pageSizeParam'=>false]);
        $arrMessage = $objMessage->offset($objPage->offset)->limit($objPage->limit)->orderBy(['message_id' => SORT_DESC])->asArray()->all();
        $arrMessage = Message::_formatMessage($arrMessage);

        return $this->render('message',
            [
                'arrMessage' => $arrMessage,
                'objPage' => $objPage,
            ]);
    }

    public function actionReim()
    {
        $arrUserInfo = $this->_getLoginUserInfo();
        if ($arrUserInfo){
            $this->view->params['isLogin'] = true;
            $this->view->params['login_user_info'] = $arrUserInfo;
        }else{
            $this->view->params['isLogin'] = false;
        }
        return $this->render('reim');
    }

    public function actionStatistics()
    {
        $arrUserInfo = $this->_getLoginUserInfo();
        if ($arrUserInfo){
            $this->view->params['isLogin'] = true;
            $this->view->params['login_user_info'] = $arrUserInfo;
        }else{
            $this->view->params['isLogin'] = false;
        }

        $intInstrumentId = intval(Yii::$app->request->get('instrument_id',0));

        $strTab = '';
        $arrInstrumentInfo = array();
        if (!empty($intInstrumentId)) {
            $arrOutput = Instrument::_getInstrumentInfoById(array($intInstrumentId));
            if ($arrOutput['error_code'] == Yii::$app->params['errorCode']['success']){
                $arrInstrumentInfo = $arrOutput['data'];
                $arrInstrumentInfo = Instrument::_formatInstrumentInfo($arrInstrumentInfo);
                $arrInstrumentInfo = $arrInstrumentInfo[0];
            }
            $strTab = 'instrument_statistics';
        }

        return $this->render('statistics',[
            'strTab' => $strTab,
            'arrInstrumentInfo' => $arrInstrumentInfo,
        ]);
    }

    public function actionUsers()
    {
        $arrUserInfo = $this->_getLoginUserInfo();
        if ($arrUserInfo){
            $this->view->params['isLogin'] = true;
            $this->view->params['login_user_info'] = $arrUserInfo;
        }else{
            $this->view->params['isLogin'] = false;
        }
        $strSearchUserName = Yii::$app->request->get('search_user_name','');

        if (!empty($strSearchUserName)){
            // 搜索
            $objSearchUser=  User::find()->where("user_name like '%$strSearchUserName%'");
            $intUsersCount = $objSearchUser->count();
            $objPage = new Pagination(['totalCount' => $intUsersCount,'pageSize' => Yii::$app->params['pageSize']['users'],'pageParam' => 'user_page','pageSizeParam'=>false]);
            $arrUsers = $objSearchUser->select('user_id')->offset($objPage->offset)->limit($objPage->limit)->asArray()->all();
        }else{
            $intUsersCount = User::find()->count();
            $objPage = new Pagination(['totalCount' => $intUsersCount,'pageSize' => Yii::$app->params['pageSize']['users'],'pageParam' => 'user_page','pageSizeParam'=>false]);
            // 课题组列表
            $arrUsers = User::find()->select('user_id')->offset($objPage->offset)->limit($objPage->limit)->asArray()->all();
        }

        $arrUserId = array();
        foreach ($arrUsers as $item){
            $arrUserId[] = $item['user_id'];
        }
        $arrUsersInfo = User::_getUserAllInfo($arrUserId);
        $arrUsersInfo = User::_formatUserInfo($arrUsersInfo);
        return $this->render('users',['arrUsers'=>$arrUsersInfo,'objPage'=>$objPage]);
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

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = false;
        $bolIsLogin = Yii::$app->session['user']['isLogin'];
        $intUserId = Yii::$app->session['user']['user_id'];
        if ($bolIsLogin && $intUserId){
            $this->redirect(['lims/homepage']);
            Yii::$app->end();
        }

        $objUserModel = new User();
        if (Yii::$app->request->isPost){
            $arrPost = Yii::$app->request->post();
            if($objUserModel->login($arrPost))
            {
                $this->redirect(['lims/homepage']);
                Yii::$app->end();
            }
        }
        return $this->render('login', [
            'model' => $objUserModel,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->session->removeAll();
        if(empty(Yii::$app->session['user']['isLogin'])){
            $this->redirect(['lims/login']);
            Yii::$app->end();
        }else{
            $this->goBack();
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionRegister()
    {
        $this->layout = false;
        $objUserModel = new User();

        if (Yii::$app->request->isPost){
            $arrPost = Yii::$app->request->post();
            if($objUserModel->register($arrPost))
            {
                $this->redirect(['lims/login']);
                Yii::$app->end();
            }
        }

        $objGroupsModel =  Group::find();
        $arrGroups = $objGroupsModel->select(['group_id','group_name','group_admin_id'])->asArray()->all();
        $arrGroups = Group::_formatGroupInfo($arrGroups);
        $arrOrganizationStructure = Organization::_getOrganizationStructure();
        $arrOrganizationMap = Organization::_getOrganizationMap();


        return $this->render('register', [
            'objUserModel' => $objUserModel,
            'arrGroups' => $arrGroups,
            'arrOrganizationStructure' => $arrOrganizationStructure,
            'arrOrganizationMap' => $arrOrganizationMap,
        ]);
    }

    public function actionForgetpassword()
    {
        $this->layout = false;
        $objUserModel = new User();
        $strNotice = '';
        if (Yii::$app->request->isPost){
            $arrPost = Yii::$app->request->post();
            $arrOutput = $objUserModel->forgetpassword($arrPost);
            $strNotice = $arrOutput['error_msg'];
            if($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success'])
            {
                $strNotice = '已发送邮件，请前往邮箱查收';
            }
        }

        return $this->render("forgetpassword",[
            'user_model' => $objUserModel,
            'strNotice' => $strNotice,
        ]);
    }


    public function actionChangepassword()
    {
        $this->layout = false;

        $strToken = trim(Yii::$app->request->get('token',0));
        $intUserId = intval(Yii::$app->request->get('id',0));
        $intTime = intval(Yii::$app->request->get('time',0));
        $bolCheck = false;
        $strNotice = '';
        $strEmail = '';
        $arrData = User::find()->where(['user_id'=>$intUserId])->asArray()->one();
        if (empty($arrData)){
            $strNotice = '请求异常';
        }else{
            $strEmail = $arrData['email'];
            $intNowTime = time();
            $strCode = $strEmail . $intTime . Yii::$app->params['email_salt'];
            $strNowToken = trim(md5($strCode));
            if ($intNowTime - $intTime > Yii::$app->params['email_time_limit'] ){
                // 1小时内有效
                $strNotice = '链接已超时';
            }elseif ($strNowToken != $strToken){
                $strNotice = '请求异常';
            }else{
                $bolCheck = true;
            }
        }
        if ($bolCheck = true && Yii::$app->request->isPost)
        {
            $arrPost = Yii::$app->request->post();
            $strPassword = $arrPost['password'];
            $strPasswordRepeat = $arrPost['password_r'];
            if ($strPassword != $strPasswordRepeat){
                $bolCheck = true;
                $strNotice = '密码不一致';
            }else{
                $arrInput = array(
                    'user_id' => $intUserId,
                    'password' => $strPassword,
                );
                $objUserModel = new User();
                $arrOutput = $objUserModel->changePassword($arrInput);
                if ($arrOutput['error_code'] ==  Yii::$app->params['errorCode']['success']){
                    $bolCheck = false;
                    $strNotice = '修改密码成功';
                }else{
                    $strNotice = '修改密码失败';
                }
            }
        }
        return $this->render("changepassword",[
            'bolCheck' => $bolCheck,
            'strEmail' => $strEmail,
            'strNotice' => $strNotice,
        ]);

    }

}

<?php

namespace app\models;
use Yii;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    const USER_TYPE_INSTRUMENT_ADMIN = 20;
    const USER_TYPE_GROUP_ADMIN = 10;
    // 系统管理员
    const USER_TYPE_SYSTEM_ADMIN = 100;

    public $password_repeat;
    public $remember = true;
    public $authKey;
    public $accessToken;

    public static function tableName(){
        return "user_info";
    }

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

    /**
     * 前端展示用标签名
     */
    public function attributeLabels()
    {
        return [
            'user_name' => '姓名',
            'email' => '邮箱',
            'password' => '密码',
            'remember' => '记住我',
        ];
    }

    public function rules()
    {
        return [
            ['email','required','message' => '邮箱地址不可为空'],
            ['email','string', 'max' => 60,  'message' => '邮箱地址不合理'],
            ['email','email',  'message' => '邮箱格式不合理'],
            ['password','required','message' => '密码不可为空'],
            //['password','string','max' => 20,'message' => '密码长度为8到20个字符'],
            // fixme
            //['password','string','min'=> 8,'message' => '密码长度为8到20个字符'],
            ['remember','boolean','on'=>'login'],
            ['password','validatePassword','on'=>'login'],
            [['password','email','user_name','user_code','user_type','organization_id'],'required', 'on'=>'register', 'message'=> '请填写该字段'],
            // ['password_repeat', 'compare', 'compareAttribute'=>'password' ,'on'=>'register' , 'message' => '重复密码和密码不一致'],
        ];
    }

    public function scenarios()
    {
        $parent_scenarios = parent::scenarios();//继承父类的场景
        //定义自己的场景
        $self_scenarios =  [
            'login' => ['email','password'],//登录的时候需要的字段
            'register' => ['email','password','user_name','user_code','user_type','organization_id'],//注册的时候需要的字段
            'update' => ['user_name'],//注册的时候需要的字段
        ];
        //合并场景
        return  array_merge($parent_scenarios,$self_scenarios);
    }

    public function login($arrData)
    {
        $this->scenario = 'login';
        if($this->load($arrData) && $this->validate())
        {
            $arrUserId = User::find()->select('user_id')->where('email=:email',[':email' => $this->email])->asArray()->one();
            $intUserId = $arrUserId['user_id'];
            $intLifeTime = $this->remember ? 7 * 24 * 3600 : 0;
            session_set_cookie_params($intLifeTime,'/');
            $objSession = Yii::$app->getSession();
            $objSession->open();
            $objSession['user'] = [
                'user_id' => $intUserId,
                'email' => $this->email,
                'isLogin' => 1,
            ];
            return boolval($objSession['user']['isLogin']);
        }
        return false;

    }

    public function register($arrData)
    {
        $this->scenario = 'register';

        $strEmail = $arrData['User']['email'];
        $arrExistUser = self::find()->select(['user_id'])->where('email=:email',[':email'=>$strEmail])->asArray()->one();
        if (!empty($arrExistUser)){
            $this->addError("email", '该邮箱已被注册。');
            return false;
        }

        $arrData['User']['password'] = md5($arrData['User']['password']);
        $arrData['User']['password_repeat'] = md5($arrData['User']['password_repeat']);
        if ($arrData['User']['password_repeat'] != $arrData['User']['password']){
            $this->addError("password_repeat", '两次输入的密码不一致');
            return false;
        }
        if($this->load($arrData) && $this->validate())
        {

            //$this->password = md5($this->password);
            if ($this->save()){
                $arrOutput = self::find()->select(['user_id'])->where('email=:email',[':email'=>$arrData['User']['email']])->asArray()->one();
                $intUserId = intval($arrOutput['user_id']);
                $intGroupId =  intval($arrData['User']['group_id']);
                if ($intGroupId != 0){
                    $objGroupUserModel = new GroupUser();
                    $objGroupUserModel->group_id = $intGroupId;
                    $objGroupUserModel->user_id = $intUserId;
                    $objGroupUserModel->status = GroupUser::STATUS_INIT;
                    $objGroupUserModel->join_time = time();
                    $objGroupUserModel->save();
                }
            }
            return true;
        }
        return false;

    }


    public function forgetpassword($arrData)
    {
        $strEmail = $arrData['User']['email'];
        if (empty($strEmail) || !filter_var($strEmail, FILTER_VALIDATE_EMAIL)){
            return returnFormat(Yii::$app->params['errorCode']['param_error'],'','格式不正确');
        }

        $arrData = self::find()->where(['email'=>$strEmail])->asArray()->one();
        if (empty($arrData)){
            return returnFormat(Yii::$app->params['errorCode']['param_error'],'','此邮箱未注册过账号');
        }
        $intUserId = $arrData['user_id'];
        $intTime = time();
        $strCode = $strEmail . $intTime . Yii::$app->params['email_salt'];
        $strToken = md5($strCode);
        $strLink = 'http://' . Yii::$app->params['server_ip'] .
            Yii::$app->params['server_path'] . 'index.php?r=lims/changepassword&time=' . $intTime . '&token=' . $strToken . '&id='.$intUserId;
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($strEmail);
        $mail->setSubject("xwlims-重置密码");
        $mail->setHtmlBody("<br>点击下面链接进行重置密码的操作(1小时内有效),请勿将链接透漏给他人 <br> <a>$strLink</a>");
        if($mail->send()) {
            return returnFormat(Yii::$app->params['errorCode']['success']);
        }
        else{
            return returnFormat(Yii::$app->params['errorCode']['fail'], '', '此邮箱未注册过账号');
        }

    }

    public function changePassword($arrInput)
    {
        $intUserId = intval($arrInput['user_id']);
        $strPassword = strval($arrInput['password']);
        if (empty($intUserId) || '' == $strPassword){
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $objUser = self::find()->where(['user_id'=>$intUserId])->one();
        $objUser->password = md5($strPassword);
        if ($objUser->update() != false){
            return returnFormat(Yii::$app->params['errorCode']['success']);
        }else{
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    public function updateInfo($arrData)
    {
        $this->scenario = 'update';
        $arrUpdateUserInfo = $arrData['User'];
        $intUserId = $arrUpdateUserInfo['user_id'];
        $objUser = self::find()->where('user_id=:user_id',[':user_id'=>$intUserId])->one();

        $objUser->user_code = $arrUpdateUserInfo['user_code'];
        $objUser->user_name = $arrUpdateUserInfo['user_name'];
        $objUser->user_type = $arrUpdateUserInfo['user_type'];
        $objUser->user_class = $arrUpdateUserInfo['user_class'];
        $objUser->phone = $arrUpdateUserInfo['phone'];
        $objUser->address = $arrUpdateUserInfo['address'];

        if(!$objUser->update()){
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if (!$this->hasErrors()) {
            $user = self::find()->where('email = :email and password = :password',
                [":email" => $this->email,":password" => md5($this->password),])->one();
            if (is_null($user)) {
                $this->addError("email", '');
                $this->addError("password", '邮箱或密码错误');
                return false;
            }else{
                $this->user_id = $user['user_id'];
            }
            return true;
        }
    }

    public static function _formatUserInfo($arrUserInfo)
    {
        foreach ($arrUserInfo as &$item)
        {
            $intType = intval($item['user_type']);
            $item['user_type_format'] = self::_getUserTypeText($intType);
        }
        return $arrUserInfo;
    }

    public static function _getUserTypeText($intType)
    {
        $intType = intval($intType);
        $arrUserType = array(
            1 => '本科生',
            2 => '硕士研究生',
            3 => '博士研究生',
            4 => '博士后',
            5 => '其他',
            10 => '课题组负责人',
            11 => '科研助理',
            12 => '实验室管理员',
            13 => '其他',
            20 => '技术员',
            21 => '其他',
            100 => '系统管理员',
        );
        if (isset($arrUserType[$intType])){
            return $arrUserType[$intType];
        }else{
            return '';
        }
    }

    public static function _getUserAllInfo($arrUserId)
    {
        $arrRet = array();
        foreach ($arrUserId as $tmpId)
        {
            $arrInfo = User::find()->asArray()->where(['user_id'=>$tmpId])->one();
            $arrRet[$tmpId] = $arrInfo;

            $arrGroupUserInfo = GroupUser::_getUserGroupInfo($tmpId);
            $intGroupId = $arrGroupUserInfo['group_id'];
            $arrRet[$tmpId]['group_status'] = $arrGroupUserInfo['status'];
            $arrRet[$tmpId]['group_status_format'] = $arrGroupUserInfo['status_format'];

            $arrGroupInfo = Group::find()->asArray()->where(['group_id'=>$intGroupId])->one();
            $strGroupName = $arrGroupInfo['group_name'];
            $arrRet[$tmpId]['group_name'] = $strGroupName;
            $arrRet[$tmpId]['group_id'] = $intGroupId;

            $intOrganizationId = $arrRet[$tmpId]['organization_id'];

            $arrRet[$tmpId]['avatar'] = "temp";
            $arrRet[$tmpId]['organization'] = Organization::_getOrganizationByIds(array($intOrganizationId))[$intOrganizationId];


        }
        return $arrRet;
    }

    public static function _getUserName($arrUserId)
    {
        $arrInfo = self::find()->select(['user_name','user_id'])->indexBy('user_id')->asArray()->where(['user_id' => $arrUserId])->all();
        return $arrInfo;
    }
}
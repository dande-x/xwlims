<?php

namespace app\models;
use Yii;

class Instrument extends \yii\db\ActiveRecord
{
    const STR_DEFAULT_INSTRUMENT_ADMIN_NAME = '系统管理员';

    const INSTRUMENT_STATUS_NORMAL = 0;
    const INSTRUMENT_STATUS_FAULT = 1;
    const INSTRUMENT_STATUS_ABANDON = 2;

    public static function tableName(){
        return "instrument_info";
    }

    public function rules()
    {
        return [
            ['instrument_name','required',],
            ['address','required',],
            ['model_number','required',],
            ['specifications','required',],
            ['price','required',],
            ['appointment_price','required',],
            ['produce_country','string'],
            ['manufacturer','string'],
            ['manufacture_time','required',],
            ['purchase_time','required',],
            ['organization_id','required',],
            ['type_number','required',],
            ['instrument_code','required',],
            ['qualification','string'],
            ['instrument_function','string'],
            ['attachments','string'],
        ];
    }

    public function add($arrData)
    {
        $arrData['Instrument']['manufacture_time'] = strtotime($arrData['Instrument']['manufacture_time']);
        $arrData['Instrument']['purchase_time'] = strtotime($arrData['Instrument']['purchase_time']);
        $arrData['Instrument']['status'] = self::INSTRUMENT_STATUS_NORMAL;

        $objImage = $arrData['img'];

        if($this->load($arrData)  && $this->validate()){
            if ($this->save()){
                // 保存图片
                $strImgName = $this->instrument_id . '.jpg';
                move_uploaded_file($objImage, Yii::getAlias("@webroot") . Yii::$app->params['instrument_image_path']. $strImgName);

                return returnFormat(Yii::$app->params['errorCode']['success']);
            }else{
                return returnFormat(Yii::$app->params['errorCode']['fail']);
            }
        }
    }

    public function updateInfo($arrData)
    {
        $intInstId = $arrData['Instrument']['instrument_id'];
        $arrUpdateInstInfo = $arrData['Instrument'];
        $objInstrument = self::find()->where('instrument_id=:instrument_id',[':instrument_id'=>$intInstId])->one();
        $objInstrument->instrument_name = $arrUpdateInstInfo['instrument_name'];
        $objInstrument->address = $arrUpdateInstInfo['address'];
        $objInstrument->model_number = $arrUpdateInstInfo['model_number'];
        $objInstrument->specifications = $arrUpdateInstInfo['specifications'];
        $objInstrument->price = $arrUpdateInstInfo['price'];
        $objInstrument->appointment_price = $arrUpdateInstInfo['appointment_price'];
        $objInstrument->produce_country = $arrUpdateInstInfo['produce_country'];
        $objInstrument->manufacturer = $arrUpdateInstInfo['manufacturer'];
        $objInstrument->type_number = $arrUpdateInstInfo['type_number'];
        $objInstrument->instrument_code = $arrUpdateInstInfo['instrument_code'];
        $objInstrument->qualification = $arrUpdateInstInfo['qualification'];
        $objInstrument->instrument_function = $arrUpdateInstInfo['instrument_function'];
        $objInstrument->attachments = $arrUpdateInstInfo['attachments'];
        $objInstrument->status = $arrUpdateInstInfo['status'];

        $objImage = $arrData['img'];
        $strImgName = $intInstId . '.jpg';
        move_uploaded_file($objImage, Yii::getAlias("@webroot") . Yii::$app->params['instrument_image_path']. $strImgName);
        if ($objInstrument->update() != false){
            return returnFormat(Yii::$app->params['errorCode']['success']);
        }else{
            return returnFormat(Yii::$app->params['errorCode']['fail']);
        }
    }

    /**
     * 前端展示用标签名
     */
    public function attributeLabels()
    {
        return [
            'instrument_name' => '仪器名',
            'address' => '仪器地址',
            'model_number' => '型号',
            'specifications' => '规格',
            'price' => '价格',
            'appointment_price' => '使用价格',
            'produce_country' => '生产国家',
            'manufacturer' => '生产厂家',
            'manufacture_time' => '出厂时间',
            'purchase_time' => '购置时间',
            'organization' => '所属组织',
            'organization_id' => '组织id',
            'type_number' => '分类号',
            'instrument_code' => '仪器编号',
            'qualification' => '技术指标',
            'instrument_function' => '主要功能及特色',
            'attachments' => '附件及配置',
        ];
    }

    public static function _formatInstrumentInfo($arrInst)
    {
        foreach ($arrInst as &$item)
        {
            $intOrganizationId = $item['organization_id'];
            $item['organization'] = Organization::_getOrganizationByIds(array($intOrganizationId))[$intOrganizationId];

            $item['admin_user_name'] = 'temp';

            $item['is_follow'] = 0;
            $item['status_format'] = self::_getStatusFormat($item['status']);
            $item['manufacture_time_format'] = date('Y/m/d H:i:s',$item['manufacture_time']);
            $item['purchase_time_format'] = date('Y/m/d H:i:s',$item['purchase_time']);
        }
        return $arrInst;
    }

    public static function _getInstrumentAdminInfo($arrInst) {

        $objInstAdminModel = new InstrumentAdmin();
        $arrAdminIdOutput = $objInstAdminModel->getInstrumentAdminByInst($arrInst);
        $arrAdminId = $arrAdminIdOutput['data'];
        $arrUserId = array();
        foreach ($arrAdminId as $item){
            $arrUserId[] = intval($item['user_id']);
        }
        $arrUserName = User::_getUserName($arrUserId);
        foreach ($arrInst as &$item){
            $intInstId = $item['instrument_id'];
            if (isset($arrAdminId[$intInstId]['user_id'])){
                $intAdminId = intval($arrAdminId[$intInstId]['user_id']);
                $item['admin_user_name'] = $arrUserName[$intAdminId]['user_name'];
                $item['admin_user_id'] = $intAdminId;
            }else{
                $item['admin_user_name'] = self::STR_DEFAULT_INSTRUMENT_ADMIN_NAME;
            }
        }
        return $arrInst;

    }

    public static function _getInstrumentFollowInfo($arrInst,$intUserId)
    {
        $objFollowModel = new  FollowInstrument();
        foreach ($arrInst as &$item)
        {
            $arrInput = array(
                'user_id' => $intUserId,
                'instrument_id' => $item['instrument_id'],
            );
            $arrOutput = $objFollowModel->getFollow($arrInput);
            $intIsFollow = intval($arrOutput['data']['is_follow']);
            $item['is_follow'] = $intIsFollow;
        }
        return $arrInst;
    }

    public static function _getInstrumentInfoById($arrInstrumentId)
    {
        if (empty($arrInstrumentId)){
            return returnFormat(Yii::$app->params['errorCode']['param_error']);
        }
        $arrInstrument = self::find()->where(['instrument_id' => $arrInstrumentId])->asArray()->all();
        return returnFormat(Yii::$app->params['errorCode']['success'],$arrInstrument);
    }

    public static function _getStatusFormat($intStatus)
    {
        switch ($intStatus){
            case 0:
                return '正常';
            case 1:
                return '仪器故障';
            case 2:
                return '已废弃';
            default:
                return '状态异常';
        }
    }

    public static function _getInstrumentName($arrInstrumentId)
    {
        $arrInfo = self::find()->select(['instrument_name','instrument_id'])->indexBy('instrument_id')->asArray()->where(['instrument_id' => $arrInstrumentId])->all();
        return $arrInfo;
    }

}
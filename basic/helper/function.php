<?php

function returnFormat($intErrorCode,$arrData=array(),$strMessage = '')
{
    if ($strMessage == '')
    {
        $strMessage = Yii::$app->params['errorMsg'][$intErrorCode];
    }
    return array(
        'error_code' => $intErrorCode,
        'data' => $arrData,
        'error_msg' => $strMessage,
    );
}
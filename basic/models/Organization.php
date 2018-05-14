<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Organization extends Model
{

    private static $arrStructure = array(
        '西北农林科技大学' => array(
            '信息工程学院',
            '农学院',
            '植保学院',
            '理学院',
            '生命科学院',

        ),
        '校外用户' => array(),
    );

    private static  $arrMap = array(
        1 => '西北农林科技大学',
        10 => '信息工程学院',
        11 => '农学院',
        12 => '植保学院',
        13 => '理学院',
        14 => '生命科学院',
        2 => '校外用户',
    );

    public static function _getOrganizationStructure()
    {
        return self::$arrStructure;
    }

    public static function _getOrganizationMap()
    {
        return self::$arrMap;
    }

    public static function _getOrganizationByIds($arrId)
    {
        $arrRet = array();
        foreach ($arrId as $intId)
        {
            $arrRet[$intId] = self::_getOrganizationName($intId);
        }
        return $arrRet;
    }

    private static function _getOrganizationName($intId)
    {
        $arrMap = array(
            1 => '西北农林科技大学',
            2 => '校外用户',
            10 => '信息工程学院',
            11 => '农学院',
            12 => '植保学院',
            13 => '理学院',
            14 => '生命科学院',
        );
        if (!isset($arrMap[$intId])){
            return '未知';
        }
        $strName = self::$arrMap[$intId];
        $strOrganization = '';
        foreach (self::$arrStructure as $strSchool => $tmpArr)
        {
            if ($strSchool == $strName){
                $strOrganization = $strSchool;
            }

            if ($strSchool != $strName && is_array($tmpArr)){
                foreach ($tmpArr as $item){
                    if ($item == $strName){
                        $strOrganization = $strSchool . '--' .$item;
                        break 2;
                    }
                }
            }
        }
        return $strOrganization;
    }
}
<?php

namespace ZhMead\XmnkBikeControl\Common;

interface ControlInterface
{
    public static function bell($box_no);
    public static function openLock($box_no);
    public static function closeLock($box_no);
    public static function temporaryCloseLock($box_no);
    public static function temporaryOpnLock($box_no);
    public static function bellBike($box_no);
    public static function openBatteryLock($box_no);
    public static function closeBatteryLock($box_no);
    public static function outAreaPlayVideo($box_no);
    public static function playVideo($box_no);
    public static function outAreaLoseElectric($box_no);
    public static function outAreaGetElectric($box_no);
    public static function selectBoxSetting($box_no);
    public static function selectBikeStatus($box_no);
    public static function rebootBox($box_no);
    public static function nowBikeLocation($box_no);
    public static function nowBikeUpLocation($box_no);
    public static function nowBikeBatteryMSG($box_no);
    public static function setBoxSetting($box_no);
}

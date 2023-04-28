<?php

namespace ZhMead\XmnkBikeControl\Common;

interface ControlInterface
{

    public function bell($box_no, $isSync);

    public function openLock($box_no, $isSync);

    public function closeLock($box_no, $isSync);

    public function temporaryCloseLock($box_no, $isSync);

    public function temporaryOpnLock($box_no, $isSync);

    public function openBatteryLock($box_no, $isSync);

    public function closeBatteryLock($box_no, $isSync);

    public function outAreaPlayVideo($box_no, $isSync);

    public function playVideo($box_no, $video_cmd, $isSync);

    public function outAreaLoseElectric($box_no, $isSync);

    public function outAreaGetElectric($box_no, $isSync);

//    public function openHelmet($box_no, $isSync);
//
//    public function cloneHelmet($box_no, $isSync);
//
//    public function selectHelmetStatus($box_no, $isSync);

    public function selectBoxSetting($box_no, $isSync);

    public function selectBikeStatus($box_no, $isSync);

    public function rebootBox($box_no, $isSync);

    public function nowBikeLocation($box_no, $isSync);

    public function nowBikeBatteryMSG($box_no, $isSync);

    public function setBoxSetting($box_no, $isSync);
}

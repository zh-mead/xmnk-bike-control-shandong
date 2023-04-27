<?php

namespace ZhMead\XmnkBikeControl\Common;

interface ControlInterface
{
    public function bell($box_no);

    public function openLock($box_no);

    public function closeLock($box_no);

    public function temporaryCloseLock($box_no);

    public function temporaryOpnLock($box_no);

    public function bellBike($box_no);

    public function openBatteryLock($box_no);

    public function closeBatteryLock($box_no);

    public function outAreaPlayVideo($box_no);

    public function playVideo($box_no, $video_cmd);

    public function outAreaLoseElectric($box_no);

    public function outAreaGetElectric($box_no);

    public function selectBoxSetting($box_no);

    public function selectBikeStatus($box_no);

    public function rebootBox($box_no);

    public function nowBikeLocation($box_no);

    public function nowBikeUpLocation($box_no);

    public function nowBikeBatteryMSG($box_no);

    public function setBoxSetting($box_no);
}

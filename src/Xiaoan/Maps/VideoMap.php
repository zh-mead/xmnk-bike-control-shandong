<?php

namespace ZhMead\XmnkBikeControl\Xiaoan\Maps;

class VideoMap
{
    //车辆设防提示音(锁车成功)
    const LOCA_SUCCESS_SOUND = 1;

    //车辆撤防提示音(开锁成功)
    const UNLOCK_SUCCESS_SOUND = 2;

    //车辆启动提示音(开始启动)
    const START_UP_SOUND = 3;

    //运维专用寻车音(滴滴滴)
    const OPEX_SPECIAL_CAR_SOUND = 4;

    //扫码成功提示音(欢迎使用电单车)
    const SCAN_SUCCESS_PROMPT_SOUND = 5;

    //临时锁车提示音(临时锁车成功)
    const TEMPORARY_LOCK_PROMPT_SOUND = 7;

    //驶出服务区提示音(车辆已驶出服务区,请尽快骑回)
    const EXIT_SERVICE_AREA_PROMPT_SOUND = 8;

    //寻车音(我在这里)
    const CAR_SEARCH_SOUND = 9;

    //临近服务区提示音(临近服务区,驶出将断电)
    const NEAR_SERVICE_AREA_SOUND = 10;

    //超速提示音
    const OVERSPEED_WARNING_SOUND = 15;

    //车辆报修提示音(车辆已报修,请试试其他车)
    const VEHICLE_REPAIR_PROMPT_SOUND = 16;

    //低电量提示音(电量过低,请试试其他车)
    const LOW_BATTERY_WARNING_SOUND = 17;

    //车辆占用提示音(车辆已被使用,请试试其他车)
    const VEHICLE_OCCUPANCY_WARNING_SOUND = 18;

    //服务区边界提示音(临近服务区边界,请尽快骑回)
    const SERVICE_AREA_BOUNDARY_PROMPT_SOUND = 19;

    //RFID感应提示音
    const RFID_INDUCTION_PROMPT_SOUND = 27;

    //倾倒告警提示音(我跌倒啦)
    const DUMP_ALARM_PROMPT_SOUND = 33;

    //操作失败提示音
    const OPERATION_FAILURE_PROMPT_SOUND = 35;
}
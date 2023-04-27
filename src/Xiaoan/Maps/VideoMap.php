<?php

namespace ZhMead\XmnkBikeControl\Xiaoan\Maps;

class VideoMap
{
    //    欢迎使用共享电单车
    const VIDEO_WELCOME = '01';
//    没电了，请换一辆车
    const VIDEO_NO_ELECTRIC = '02';
//    骑出运营区，请尽快返回
    const VIDEO_OUT_AREA = '03';
//    报警音,车辆未解锁，请扫描骑行
    const VIDEO_WARNING = '04';
//    HI,主人，我在这辆车。
    const VIDEO_BIKE_HERE = '05';
//    还车成功，欢迎再次使用共享电单车
    const VIDEO_CLOSE_BIKE = '06';
//    寻车提示音
    const VIDEO_FIND_BIKE = '07';
//    临时锁车
    const VIDEO_T_CLOSE_BIKE = '08';
//    当前不在停车点，请在手机查看停车点
    const VIDEO_NO_STOP_SITE = '09';
//    电池锁已打开
    const VIDEO_BATTERY_OPEN = '0A';
//    电池锁已关闭
    const VIDEO_BATTERY_CLOSE = '0B';
//    距离服务区过远，车辆即将断电。
    const VIDEO_OUT_AREA_CLOSE_ELECTRIC = '0C';
//    已超速，请注意减速慢行
    const VIDEO_OUT_SPEED = '0D';
//    车铃音
    const VIDEO_BIKE_DELL = '0E';
}
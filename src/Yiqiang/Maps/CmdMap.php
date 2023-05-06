<?php

namespace ZhMead\XmnkBikeControl\Yiqiang\Maps;

class CmdMap
{
    //设置防盗开关(cmd:4)
    const COMMAND_ANTITHEFT_SWITCH = 4;

    //修改服务器地址(cmd:11)
    const COMMAND_MODIFY_SERVER_ADDRESS = 11;

    //查询服务器地址(cmd:16)
    const COMMAND_QUERY_SERVER_ADDRESS = 16;

    //控制语音播报(cmd:14)
    const COMMAND_CONTROL_VOICE_BROADCAST = 14;

    //设备重启(cmd:21)
    const COMMAND_DEVICE_RESTART = 21;

    //开关后轮锁(cmd:28)
    const COMMAND_REARWHEEL_LOCK = 28;

    //设置蓝牙操作模式(cmd:26)
    const COMMAND_BLUETOOTH_OPERATION_MODE = 26;

    //打开电池仓锁(cmd:29)
    const COMMAND_OPEN_BATTERY_COMPARTMENT_LOCK = 29;

    //单条语音定制(cmd:30)
    const COMMAND_SINGLE_VOICE_CUSTOMIZATION = 30;

    //查询内部参数(cmd:31)
    const COMMAND_QUERY_INTERNAL_PARAMETERS = 31;

    //设置内部参数(cmd:32)
    const COMMAND_SET_INTERNAL_PARAMETERS = 32;

    //启动/熄火车辆(cmd:33)
    const COMMAND_STARTORSTOP_VEHICLE = 33;

    //查询设备状态信息(cmd:34)
    const COMMAND_QUERY_DEVICE_STATUS_INFO = 34;

    //远程升级(cmd:35)
    const COMMAND_REMOTE_UPGRADE = 35;

    //查询定制语音列表(cmd:36)
    const COMMAND_QUERY_CUSTOM_VOICE_LIST = 36;

    //开关电池仓锁(cmd:40)
    const COMMAND_SWITCH_BATTERY_COMPARTMENT_LOCK = 40;

    //获取BMS实时数据(cmd:41)
    const COMMAND_OBTAIN_BMS_REALTIME_DATA = 41;

    //获取BMS固定数据(cmd:42)
    const COMMAND_OBTAIN_BMS_FIXED_DATA = 42;

    //设置BMS参数(cmd:43)
    const COMMAND_SET_BMS_PARAMETERS = 43;

    //获取控制器数据(cmd:44)
    const COMMAND_OBTAIN_CONTROLLER_DATA = 44;

    //设置控制器限速(cmd:45)
    const COMMAND_SET_CONTROLLER_SPEED_LIMIT = 45;

    //设置控制器欠压值(cmd:46)
    const COMMAND_SET_CONTROLLER_UNDERVOLTAGE = 46;

    //设置控制器限流值(cmd:47)
    const COMMAND_SET_CONTROLLER_CURRENT_LIMIT = 47;

    //设置控制器缓启动(cmd:48)
    const COMMAND_SET_CONTROLLER_SLOW_START = 48;

    //设置蓝牙参数(cmd:49)
    const COMMAND_SET_BLUETOOTH_PARAMETERS = 49;

    //更新内置围栏数据(cmd:50)
    const COMMAND_UPDATE_BUILT_FENCE_DATA = 50;

    //开关控制器大灯(cmd:56)
    const COMMAND_SWITCH_CONTROLLER_HEADLIGHT = 56;

    //整套语音定制(cmd:57)
    const COMMAND_COMPLETE_VOICE_CUSTOMIZATION = 57;

    //获取基站定位(cmd:73)
    const COMMAND_OBTAIN_BASE_STATION_POSITION = 73;

    //获取基站信息(cmd:74)
    const COMMAND_OBTAIN_BASE_STATION_INFO = 74;

    //开关头盔锁(cmd:82)
    const COMMAND_SWITCH_HELMET_LOCK = 82;

    //查询蓝牙道钉信息(cmd:85)
    const COMMAND_QUERY_BLUETOOTH_SPIKE_INFO = 85;
}
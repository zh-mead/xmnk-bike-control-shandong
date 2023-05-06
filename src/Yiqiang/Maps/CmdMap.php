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
    //const COMMAND_BLUETOOTH_OPERATION_MODE = 26;

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
    //const COMMAND_QUERY_CUSTOM_VOICE_LIST = 36;

    //开关电池仓锁(cmd:40)
    //const COMMAND_SWITCH_BATTERY_COMPARTMENT_LOCK = 40;

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
    //const COMMAND_COMPLETE_VOICE_CUSTOMIZATION = 57;

    //获取基站定位(cmd:73)
    const COMMAND_OBTAIN_BASE_STATION_POSITION = 73;

    //获取基站信息(cmd:74)
    const COMMAND_OBTAIN_BASE_STATION_INFO = 74;

    //超级权限锁车(cmd:78)
    const COMMAND_SUPER_PERMISSION_LOCK = 78;

    //同步设备时间指令(cmd:74)
    const COMMAND_SYNCHRONOUS_DEVICE_TIME_COMMAND = 79;

    //设置电摩能量回馈参数(cmd:81)
    const COMMAND_ENERGY_FEEDBACK_PARAMETERS = 81;

    //电摩控制器系统锁车(cmd:82)
    const COMMAND_CONTROLLER_SYSTEM_LOCK = 82;

    //开关头盔锁(cmd:82)
    //const COMMAND_SWITCH_HELMET_LOCK = 82;

    //电摩控制器用户锁车(cmd:83)
    const COMMAND_CONTROLLER_USER_LOCK = 83;

    //临时停车(cmd:84)
    const COMMAND_TEMPORARY_PARK = 84;

    //临时停车继续骑行(cmd:85)
    const COMMAND_TEMPORARY_PARK_CONTINUE_CYCLING = 85;

    //查询蓝牙道钉信息(cmd:85)
    //const COMMAND_QUERY_BLUETOOTH_SPIKE_INFO = 85;

    //获取蓝牙道钉信息(cmd:86)
    const COMMAND_QUERY_BLUETOOTH_SPIKE_INFO = 86;

    //获取周边wifi信息(cmd:87)
    const COMMAND_OBTAIN_WIFI_INFO = 87;

    //寻车指令(cmd:88)
    const COMMAND_CAR_SEARCH_COMMAND = 88;

    //打开头盔锁 (cmd:89)
    const COMMAND_OPEN_HELMET_LOCK = 89;

    //设置限速模式 (cmd:91)
    const COMMAND_SPEED_LIMIT_MODE = 91;


    //获取当前车辆航向角 (cmd:92)
    const COMMAND_OBTAINN_CURRENT_VEHICLE_ANGLE = 92;

    //角度校准(cmd:93)
    const COMMAND_ANFLE_CALIBRATION = 93;

    //复位电池仓锁(cmd:94)
    const COMMAND_RESET_BATTERY_COMPARTMENT_LOCK = 94;

    //摄像头90度停车接口(cmd:95)
    const COMMAND_CAMERA_DEFREE_PARK_INNTERFACE = 95;

    //读取站点卡片UID (cmd:96)
    const COMMAND_READ_SITE_CARD_UID = 96;

    //同步剩余电量和剩余里程 (cmd:97)
    const COMMAND_SYNCHRONIZE_REMAIN_BATTERY = 97;

    //设置和控制器通信接口类型 (cmd:98)
    const COMMAND_SET_CONTROLLER_COMMUNICATION_TYPE = 98;

    //设置畅听雷达避障功能 (cmd:99)
    const COMMAND_SET_OBSTACLE_AVOIDANCE_FUNCTION = 99;

    //摄像头90度查询接口(cmd:100)
    const COMMAND_CAMERA_DEGREE_QUERY_INTERFACE = 100;

    //沙滩车激活/休眠电池远程控制指令(cmd:151)
    const COMMAND_BEACH_CAR_BATTERY_COMMAND = 151;
}
<?php

require __DIR__ . '/vendor/autoload.php';

use ZhMead\XmnkBikeControl;
use ZhMead\XmnkBikeControl\Common\Maps\DeviceMap;
use ZhMead\XmnkBikeControl\Common\Maps\UserTypeMap;

$config = [
    'registerGateways' => [
        'tbit' => [
            'registerAddress' => '127.0.0.1:12238'
        ],
        'xiaoan' => [
//            'registerAddress' => '127.0.0.1:12238'
            'registerAddress' => '10.206.0.8:12238'
        ],
//        'weikemu' => [
//            'registerAddress' => '127.0.0.1:12238'
//        ],
    ],
    'defaultGateway' => DeviceMap::TBit,
    'redis' => [
        'host' => 'node3',
        'port' => 6379,
        'password' => 'lV3oNUXv',
        'database' => 10
    ],
    'isSyncCmd' => false,
    'userTypeTag' => UserTypeMap::USER,
    'isDev' => true,
];

$bikeControl = new XmnkBikeControl\BikeControl($config);
//$a = $bikeControl->device(DeviceMap::XiaoAn)->bell('861037059456905', true);
//$a = $bikeControl->device(DeviceMap::XiaoAn)->selectBoxSetting('861037059456905');
//$a = $bikeControl->device(DeviceMap::XiaoAn)->selectBoxServerUrl('861037059456905');
$a = $bikeControl->device(DeviceMap::XiaoAn)->nowBikeLocation('861037059456905');
$a = $bikeControl->device(DeviceMap::XiaoAn)->openLock('861037059456905');
//$a = $bikeControl->device(DeviceMap::XiaoAn)->closeLock('861037059456905');
var_dump($a);
//$bikeControl->device(DeviceMap::XiaoAn)->openLock('123456');
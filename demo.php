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
            'registerAddress' => '127.0.0.1:12239'
        ],
//        'weikemu' => [
//            'registerAddress' => '127.0.0.1:12238'
//        ],
    ],
    'defaultGateway' => DeviceMap::TBit,
    'redis' => [
        'host' => '127.0.0.1',
        'port' => '6379',
        'password' => '',
        'database' => 0,
    ],
    'isSyncCmd' => false,
    'userTypeTag' => UserTypeMap::USER,
];

$bikeControl = new XmnkBikeControl\BikeControl($config);
//$bikeControl->device(DeviceMap::XiaoAn)->openLock('123456');
$bikeControl->device(DeviceMap::TBit)->bell('563');
//$bikeControl->device(DeviceMap::XiaoAn)->openLock('123456');
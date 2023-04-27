<?php

require __DIR__ . '/vendor/autoload.php';

use ZhMead\XmnkBikeControl;
use ZhMead\XmnkBikeControl\Common\Maps\DeviceMap;

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

    ],

];

$bikeControl = new XmnkBikeControl\BikeControl($config);
$bikeControl->device(DeviceMap::XiaoAn)->openLock('123456');
$bikeControl->device(DeviceMap::TBit)->openLock('563');
$bikeControl->device(DeviceMap::XiaoAn)->openLock('123456');
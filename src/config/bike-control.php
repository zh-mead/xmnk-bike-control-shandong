<?php

use \ZhMead\XmnkBikeControl\Common\Maps\DeviceMap;
use \ZhMead\XmnkBikeControl\Common\Maps\UserRoleMap;

return [
    'registerGateways' => [
        'tbit' => [
            'registerAddress' => '0.0.0.0:1238'
        ],
        'xiaoan' => [
            'registerAddress' => '0.0.0.0:12238'
        ],
    ],
    'numGatewayMaps' => [
        DeviceMap::NO,
        DeviceMap::TBit,
        DeviceMap::NO,
        DeviceMap::XiaoAn
    ],
    'defaultGateway' => DeviceMap::TBit,
    'redis' => [
        'host' => 'node2',
        'port' => 6379,
        'password' => '123456',
        'database' => 3
    ],
    'isSyncCmd' => false,
    'isAutoBikeStatusSync' => true,
    'addressesCacheDisable' => true,
    'userRoleTag' => UserRoleMap::USER,
    'isDev' => false,
];
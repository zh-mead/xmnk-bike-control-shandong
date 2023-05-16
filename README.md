<h1 align="center"> xmnk-bike-control </h1>

<p align="center"> .</p>


## Installing

```shell
$ composer require zh-mead/xmnk-bike-control-shandong -vvv
```

## Usage
~~~php
$config = [
    'registerGateways' => [
        'tbit' => [
            'registerAddress' => '127.0.0.1:12238'
        ],
        'xiaoan' => [
            'registerAddress' => '127.0.0.1:12238'
        ]
    ],
    'numGatewayMaps' => [
        DeviceMap::NO,
        DeviceMap::TBit,
        DeviceMap::NO,
        DeviceMap::XiaoAn,
    ],
    'defaultGateway' => DeviceMap::TBit,
    'redis' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => '',
        'database' => 3
    ],
    'isSyncCmd' => false,
    'isAutoBikeStatusSync' => true,
    'userRoleTag' => UserRoleMap::USER,
    'isDev' => true,
];

$bikeControl = new XmnkBikeControl\BikeControl($config);
$bikeControl->device(DeviceMap::XiaoAn)->bell('2011550024');
~~~
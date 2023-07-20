<h1 align="center"> xmnk-bike-control </h1>

<p align="center"> .</p>


## Installing
### 方式一
```shell
$ composer require zh-mead/xmnk-bike-control-shandong -vvv
```
### 方式二
声明自动加载
接下来我们需要在 composer.json 中声明包自动加载的命名空间
~~~
{
    .
    .
    .
    "autoload": {
        "psr-4": {
            "ZhMead\\XmnkBikeControl\\": "./src/"
        }
    },
    .
    .
    .
}
~~~

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
    'addressesCacheDisable' => false,
    'userRoleTag' => UserRoleMap::USER,
    'isDev' => true,
];

$bikeControl = new XmnkBikeControl\BikeControl($config);
$bikeControl->device(DeviceMap::XiaoAn)->bell('2011550024');//寻铃
$bikeControl->device(DeviceMap::XiaoAn)->open('2011550024',[
    'id' => $order->id,//订单id
    'bike_id' => $order->bike_id,//车辆id
    'area_id' => $order->area_id//车辆所在区域id
]);//开锁
$bikeControl->device(DeviceMap::XiaoAn)->closeLock('2011550024');//关锁
$bikeControl->device(DeviceMap::XiaoAn)->temporaryCloseLock('2011550024');//临时关锁
$bikeControl->device(DeviceMap::XiaoAn)->temporaryOpenLock('2011550024');//临时开锁
$bikeControl->device(DeviceMap::XiaoAn)->openBatteryLock('2011550024');//打开电池仓
$bikeControl->device(DeviceMap::XiaoAn)->outAreaPlayVideo('2011550024');//播放超区语音【中继端会自动播放】
$bikeControl->device(DeviceMap::XiaoAn)->playVideo('2011550024','01');//播放语音【01:语音指令】
$bikeControl->device(DeviceMap::XiaoAn)->outAreaLoseElectric('2011550024');//超区失能【中继端会自动播放】
$bikeControl->device(DeviceMap::XiaoAn)->outAreaGetElectric('2011550024');//进区加电【中继端会自动播放】
$bikeControl->device(DeviceMap::XiaoAn)->closeLowElectricLimit('2011550024');//关闭低电量限制
$bikeControl->device(DeviceMap::XiaoAn)->rebootBox('2011550024');//重启中控
$bikeControl->device(DeviceMap::XiaoAn)->nowBikeLocation('2011550024');//立即定位
$bikeControl->device(DeviceMap::XiaoAn)->nowBikeBatteryMSG('2011550024');//立即更新电量


~~~
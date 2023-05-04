<?php

namespace ZhMead\XmnkBikeControl;

use ZhMead\XmnkBikeControl\Common\BikeStatusSync;
use ZhMead\XmnkBikeControl\Common\Maps\DeviceMap;
use ZhMead\XmnkBikeControl\Common\Maps\UserTypeMap;

/**
 * Class EasySms.
 */
class BikeControl
{
    /**
     * @var
     */
    protected $controls;
    protected $control;
    protected $controlKeys = [];
    protected $defaultGateway;
    protected $redis = false;

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $configs = [])
    {
        if (count($configs)) $this->init($configs);
    }

    /**
     * 初始化配置
     * @param array $configs
     * @return $this
     * @throws \Exception
     */
    public function init(array $configs)
    {
        //连接redis
        try {
            $this->redis = new \Redis();
            $this->redis->connect($configs['redis']['host'], $configs['redis']['port']);
            if (!empty($configs['redis']['password'])) {
                $this->redis->auth($configs['redis']['password']);
            }
            $this->redis->select($configs['redis']['database']);
        } catch (\Exception $exception) {
            throw new \Exception('Redis链接失败');
        }

        //是否同步获取结果
        $isSyncCmd = false;
        if (array_key_exists('isSyncCmd', $configs)) $isSyncCmd = $configs['isSyncCmd'];

        $userTypeTag = UserTypeMap::USER;
        if (array_key_exists('userTypeTag', $configs)) $userTypeTag = $configs['userTypeTag'];

        $isDev = false;
        if (array_key_exists('isDev', $configs)) $isDev = $configs['isDev'];

        if (!array_key_exists('registerGateways', $configs)) throw new  \Exception('registerGateways找不到该配置项');

        $bikeStatusSync = new BikeStatusSync($this->redis);

        $gateways = $configs['registerGateways'];
        if (array_key_exists(DeviceMap::TBit, $gateways)) {
            $this->controls[DeviceMap::TBit] = new \ZhMead\XmnkBikeControl\Tbit\Control($gateways[DeviceMap::TBit]['registerAddress'], $bikeStatusSync, $isSyncCmd, $userTypeTag, $isDev);
            $this->controlKeys[] = DeviceMap::TBit;
        }
        if (array_key_exists(DeviceMap::XiaoAn, $gateways)) {
            $this->controls[DeviceMap::XiaoAn] = new \ZhMead\XmnkBikeControl\Xiaoan\Control($gateways[DeviceMap::XiaoAn]['registerAddress'], $bikeStatusSync, $isSyncCmd, $userTypeTag, $isDev);
            $this->controlKeys[] = DeviceMap::XiaoAn;
        }
        if (array_key_exists(DeviceMap::WeiKeMu, $gateways)) {
            $this->controls[DeviceMap::WeiKeMu] = new \ZhMead\XmnkBikeControl\Xiaoan\Control($gateways[DeviceMap::WeiKeMu]['registerAddress'], $bikeStatusSync, $isSyncCmd, $userTypeTag, $isDev);
            $this->controlKeys[] = DeviceMap::WeiKeMu;
        }

        if (!count($this->controlKeys)) {
            throw new \Exception('必须配置一个中控');
        }

        if (!array_key_exists('defaultGateway', $configs)) $configs['defaultGateway'] = $this->controlKeys[0];
        $this->defaultGateway = $configs['defaultGateway'];

        return $this;
    }

    /**
     * 切换中控类型
     * @param $type
     * @return $this
     */
    public function device($type)
    {
        if (is_numeric($type)) {
            $type = $this->controlKeys[$type];
        }

        if (!in_array($type, $this->controlKeys)) throw new \Exception('找不到该厂商');

        if (!count($this->controlKeys)) throw new \Exception('必须配置一个中控');

        $this->control = $this->controls[$type];
        return $this;
    }

    public function __call($method, $parameters)
    {
        if (!count($this->controlKeys)) throw new \Exception('必须配置一个中控');
        if ($this->control) {
            return $this->control->$method(...$parameters);
        }
        return $this->controls[$this->defaultGateway]->$method(...$parameters);
    }
}

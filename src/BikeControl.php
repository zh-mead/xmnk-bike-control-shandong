<?php

namespace ZhMead\XmnkBikeControl;

use ZhMead\XmnkBikeControl\Common\Maps\DeviceMap;

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
        $gateways = $configs['registerGateways'];

        if (array_key_exists(DeviceMap::TBit, $gateways)) {
            $this->controls[DeviceMap::TBit] = new \ZhMead\XmnkBikeControl\Tbit\Control($gateways[DeviceMap::TBit]['registerAddress']);
            $this->controlKeys[] = DeviceMap::TBit;
        }
        if (array_key_exists(DeviceMap::XiaoAn, $gateways)) {
            $this->controls[DeviceMap::XiaoAn] = new \ZhMead\XmnkBikeControl\Xiaoan\Control($gateways[DeviceMap::XiaoAn]['registerAddress']);
            $this->controlKeys[] = DeviceMap::XiaoAn;
        }
        if (array_key_exists(DeviceMap::WeiKeMu, $gateways)) {
            $this->controls[DeviceMap::WeiKeMu] = new \ZhMead\XmnkBikeControl\Xiaoan\Control($gateways[DeviceMap::WeiKeMu]['registerAddress']);
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

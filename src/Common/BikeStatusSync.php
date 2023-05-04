<?php

namespace ZhMead\XmnkBikeControl\Common;

use ZhMead\XmnkBikeControl\Common\Maps\BaseMap;

class BikeStatusSync
{
    private static $redis = '';

    //还车时过期时间
    const CLOSE_BIKE_TTL = 5;

    //正常未骑行车的缓存标识
    const REDIS_BIKE_LOCATION_TAG = 'bike_locations';
    const REDIS_RIDE_BIKE_ORDERS_TAG = 'ride_orders:';
    const REDIS_RIDE_BIKE_ORDERS_COUNT_TAG = 'statistics:ride_orders_count';

    public function __construct($redis)
    {
        self::$redis = $redis;
    }

    /**
     * 获取中控数据
     * @param $key
     * @return mixed
     */
    public function getBikeBoxInfo($key)
    {
        $data = self::$redis->get(BaseMap::CACHE_KEY . $key);
        return $data;
    }

    /**
     * 删除key
     * @param $key
     * @return mixed
     */
    public function delKeys($key)
    {
        return self::$redis->del($key);
    }

    /**
     * 车辆处于骑行状态 (用户订单时（data: 订单id[id],区域id[area_id],车辆id[bike_id]）)
     * @param $bike_no
     * @param array $data
     * User: Mead
     */
    public function toBikeRideStatus($role, $box_no, $data = [], $merchant_id = 1)
    {
        $data['role'] = $role;
        //临时锁车状态
        $data['is_temporary_close'] = 0;
        //是否超出运营区域
        $data['is_out_area_lost_electric'] = 1;
        //是否低电关电车
        $data['is_low_electric_close_bike'] = 1;
        self::$redis->set(self::REDIS_RIDE_BIKE_ORDERS_TAG . $box_no, serialize($data));

//        if (!$merchant_id) {
//            $merchant_id = Bike::byBikeNoGetMerchantId($bike_no);
//        }
        $bike_key = self::REDIS_BIKE_LOCATION_TAG . ":{$merchant_id}";
        //删除未骑行状态
        self::$redis->zrem($bike_key, $box_no);
        return true;
    }

    /**
     * 车辆处于等待骑行状态
     * @param $box_no
     * User: Mead
     */
    public function toBikeWaitRideStatus($box_no, $lng = 0, $lat = 0, $merchant_id = 1)
    {
        self::$redis->expire(self::REDIS_RIDE_BIKE_ORDERS_TAG . $box_no, self::CLOSE_BIKE_TTL);

//        if (!$merchant_id) {
//            $merchant_id = Bike::byBikeNoGetMerchantId($box_no);
//        }
        $bike_key = self::REDIS_BIKE_LOCATION_TAG . ":{$merchant_id}";
        self::$redis->geoadd($bike_key, $lng, $lat, $box_no);
        return true;
    }

    /**
     * 改变车为订单为临时停车
     * @param $box_no
     * User: Mead
     */
    public function toBikeTemporaryWaitRideStatus($box_no)
    {
        $data = $this->getRideBikeOrderInfo($box_no);
        $data['is_temporary_close'] = 1;
        self::$redis->set(self::REDIS_RIDE_BIKE_ORDERS_TAG . $box_no, serialize($data));
        return true;
    }

    /**
     * 改变车为订单为临时停车
     * @param $box_no
     * User: Mead
     */
    public function toBikeTemporaryRideStatus($box_no)
    {
        $data = $this->getRideBikeOrderInfo($box_no);
        $data['is_temporary_close'] = 0;
        self::$redis->set(self::REDIS_RIDE_BIKE_ORDERS_TAG . $box_no, serialize($data));
        return true;
    }


    /**
     * 超区是否断电
     * @param $box_no
     * @return mixed
     * Author: Mead
     */
    public function getBikeIfOutAreaLoseElectric($box_no)
    {
        $data = $this->getRideBikeOrderInfo($box_no);
        $b = isset($data['is_out_area_lost_electric']) ? $data['is_out_area_lost_electric'] : 1;
        return !$b;
    }

    /**
     * 车辆上线
     * @param $box_no
     * @param int $lat
     * @param int $lng
     * User: Mead
     */
    public function toBikeOnLineStatus($box_no, $lng = 0, $lat = 0, $merchant_id = 1)
    {
//        self::$redis->hdel(self::REDIS_RIDE_BIKE_ORDERS_TAG, $box_no);
        self::$redis->del([self::REDIS_RIDE_BIKE_ORDERS_TAG . $box_no]);

//        if (!$merchant_id) {
//            $merchant_id = Bike::byBikeNoGetMerchantId($box_no);
//        }
        $bike_key = self::REDIS_BIKE_LOCATION_TAG . ":{$merchant_id}";
        self::$redis->geoadd($bike_key, $lng, $lat, $box_no);
        return true;
    }

    /**
     * 车辆离线
     * @param $box_no
     * User: Mead
     */
    public function toBikeOffLineStatus($box_no, $merchant_id = 1)
    {
//        if (!$merchant_id) {
//            $merchant_id = Bike::byBikeNoGetMerchantId($box_no);
//        }
        $bike_key = self::REDIS_BIKE_LOCATION_TAG . ":{$merchant_id}";
        //删除未骑行状态
        self::$redis->zrem($bike_key, $box_no);
        return true;
    }

    /**
     * 获取订单信息
     * @param $box_no
     * @return mixed
     * User: Mead
     */
    public function getRideBikeOrderInfo($box_no)
    {
        $order = self::$redis->get(self::REDIS_RIDE_BIKE_ORDERS_TAG . $box_no);
        if (!$order) return false;
        return unserialize($order);
    }

    /**
     * 获取位置
     * @param $box_no
     * @return
     * Author: Mead
     */
    public function byBikeNoGetLocation($box_no)
    {
        try {
            //融合定位包
            $upLocation = self::$redis->get(BaseMap::CACHE_KEY . ":CONTROL_NOW_UP_LOCATION:{$box_no}");
            if (!$upLocation) {
                $upLocation = [
                    'lat' => 0,
                    'lng' => 0,
                    'spike' => '',
                    'time' => 0
                ];
            } else {
                $upLocation = $this->decodeData($upLocation);
            }

            //定位包
            $location = self::$redis->get(BaseMap::CACHE_KEY . ":NOW_LOCATION:{$box_no}");
            if (!$location) {
                $location = [
                    'lat' => 0,
                    'lng' => 0,
                    'mileage' => $location['mileage'],
                    'time' => 0
                ];
            } else {
                $location = $this->decodeData($location);
            }
            if ($upLocation['time'] > $location['time']) {
                return [
                    'lat' => $upLocation['lat'],
                    'lng' => $upLocation['lng'],
                    'mileage' => $location['mileage'],
                    'spike' => $upLocation['spike'],
                    'time' => $upLocation['time']
                ];
            }

            return [
                'lat' => $location['lat'],
                'lng' => $location['lng'],
                'mileage' => $location['mileage'],
                'spike' => $upLocation['spike'],
                'time' => $location['time']
            ];
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * 统计骑行的数量
     * @return void
     */
    public function statisticalRideOrder()
    {
        $rides = self::$redis->keys(self::REDIS_RIDE_BIKE_ORDERS_TAG . "*");
        $nums = count($rides);
        $r_nums = self::$redis->get(self::REDIS_RIDE_BIKE_ORDERS_COUNT_TAG) ?? 0;
        if ($r_nums < $nums) {
            self::$redis->set(self::REDIS_RIDE_BIKE_ORDERS_COUNT_TAG, $nums);
        }
    }

    /**
     * 解密信息
     * @param $str
     * @return mixed
     * Author: Mead
     */
    private function decodeData($str)
    {
        return json_decode($str, true);
    }
}
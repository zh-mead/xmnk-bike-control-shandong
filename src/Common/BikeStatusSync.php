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
     * 车辆处于骑行状态 (用户订单时（data: 订单id[id],区域id[area_id],车辆id[bike_id]）)
     * @param $bike_no
     * @param array $data
     * User: Mead
     */
    public function toBikeRideStatus($role, $bike_no, $data = [], $merchant_id = 1)
    {
        $data['role'] = $role;
        //临时锁车状态
        $data['is_temporary_close'] = 0;
        //是否超出运营区域
        $data['is_out_area_lost_electric'] = 1;
        //是否低电关电车
        $data['is_low_electric_close_bike'] = 1;
        self::$redis->set(self::REDIS_RIDE_BIKE_ORDERS_TAG . $bike_no, serialize($data));

//        if (!$merchant_id) {
//            $merchant_id = Bike::byBikeNoGetMerchantId($bike_no);
//        }
        $bike_key = self::REDIS_BIKE_LOCATION_TAG . ":{$merchant_id}";
        //删除未骑行状态
        self::$redis->zrem($bike_key, $bike_no);
        return true;
    }

    /**
     * 车辆处于等待骑行状态
     * @param $bike_no
     * User: Mead
     */
    public function toBikeWaitRideStatus($bike_no, $lng = 0, $lat = 0, $merchant_id = 1)
    {
//        self::$redis->hdel(self::REDIS_RIDE_BIKE_ORDERS_TAG . $bike_no);
        self::$redis->expire(self::REDIS_RIDE_BIKE_ORDERS_TAG . $bike_no, self::CLOSE_BIKE_TTL);

        if (!$merchant_id) {
            $merchant_id = Bike::byBikeNoGetMerchantId($bike_no);
        }
        $bike_key = self::REDIS_BIKE_LOCATION_TAG . ":{$merchant_id}";

        self::$redis->geoadd($bike_key, $lng, $lat, $bike_no);
    }

    /**
     * 改变车为订单为临时停车
     * @param $bike_no
     * User: Mead
     */
    public function toBikeTemporaryWaitRideStatus($bike_no)
    {
        $data = $this->getRideBikeOrderInfo($bike_no);
        $data['is_temporary_close'] = 1;
        self::$redis->set(self::REDIS_RIDE_BIKE_ORDERS_TAG . $bike_no, serialize($data));
    }

    /**
     * 改变车为订单为临时停车
     * @param $bike_no
     * User: Mead
     */
    public function toBikeTemporaryRideStatus($bike_no)
    {
        $data = $this->getRideBikeOrderInfo($bike_no);
        $data['is_temporary_close'] = 0;
        self::$redis->set(self::REDIS_RIDE_BIKE_ORDERS_TAG . $bike_no, serialize($data));
    }

    /**
     * 改变车为订单为开车(租车)
     * @param $bike_no
     * User: Mead
     */
    public function toBikeRentRideStatus($bike_no)
    {
        $data = $this->getRideBikeOrderInfo($bike_no);
        if (!$data) {
            $order = DB::table('rent_orders')->where('bike_no', $bike_no)->orderByDesc('id')->first();
            $data = [
                'id' => $order->id,
                'bike_id' => $order->bike_id,
                'area_id' => $order->area_id,
                'is_rent' => 1,
                'role' => 'user'
            ];
            //是否超出运营区域
            $data['is_out_area_lost_electric'] = 1;
            //是否低电关电车
            $data['is_low_electric_close_bike'] = 1;
        }
        $data['is_close_bike'] = 0;
        self::$redis->set(self::REDIS_RIDE_BIKE_ORDERS_TAG . $bike_no, serialize($data));
    }

    /**
     *
     * @param $bike_no
     * @return mixed
     * Author: Mead
     */
    public function getBikeIfOutAreaLoseElectric($bike_no)
    {
        $data = $this->getRideBikeOrderInfo($bike_no);
        $b = isset($data['is_out_area_lost_electric']) ? $data['is_out_area_lost_electric'] : 1;
        return !$b;
    }

    /**
     * 改变车为订单为停车(租车)
     * @param $bike_no
     * User: Mead
     */
    public function toBikeRentWaitRideStatus($bike_no)
    {
        $data = $this->getRideBikeOrderInfo($bike_no);
        if (!$data) {
            $order = DB::table('rent_orders')->where('bike_no', $bike_no)->orderByDesc('id')->first();
            $data = [
                'id' => $order->id,
                'bike_id' => $order->bike_id,
                'area_id' => $order->area_id,
                'is_rent' => 1,
                'role' => 'user'
            ];
            //是否超出运营区域
            $data['is_out_area_lost_electric'] = 1;
            //是否低电关电车
            $data['is_low_electric_close_bike'] = 1;
        }
        $data['is_close_bike'] = 1;
        self::$redis->set(self::REDIS_RIDE_BIKE_ORDERS_TAG . $bike_no, serialize($data));
    }

    /**
     * 车辆上线
     * @param $bike_no
     * @param int $lat
     * @param int $lng
     * User: Mead
     */
    public function toBikeOnLineStatus($bike_no, $lng = 0, $lat = 0, $merchant_id = false)
    {
//        self::$redis->hdel(self::REDIS_RIDE_BIKE_ORDERS_TAG, $bike_no);
        self::$redis->del([self::REDIS_RIDE_BIKE_ORDERS_TAG . $bike_no]);

        if (!$merchant_id) {
            $merchant_id = Bike::byBikeNoGetMerchantId($bike_no);
        }
        $bike_key = self::REDIS_BIKE_LOCATION_TAG . ":{$merchant_id}";
        self::$redis->geoadd($bike_key, $lng, $lat, $bike_no);
    }

    /**
     * 车辆离线
     * @param $bike_no
     * User: Mead
     */
    public function toBikeOffLineStatus($bike_no, $merchant_id = false)
    {
        if (!$merchant_id) {
            $merchant_id = Bike::byBikeNoGetMerchantId($bike_no);
        }
        $bike_key = self::REDIS_BIKE_LOCATION_TAG . ":{$merchant_id}";
        //删除未骑行状态
        self::$redis->zrem($bike_key, $bike_no);
    }

    /**
     * 获取订单信息
     * @param $bike_no
     * @return mixed
     * User: Mead
     */
    public function getRideBikeOrderInfo($bike_no)
    {
        $order = self::$redis->get(self::REDIS_RIDE_BIKE_ORDERS_TAG . $bike_no);
        if (!$order) return false;
        return unserialize($order);
    }

    /**
     * 获取位置
     * @param $bike_no
     * @return array
     * Author: Mead
     */
    public function byBikeNoGetLocation($bike_no)
    {
        try {
            //融合定位包
            $upLocation = self::$redis->get(BikeControl::CACHE_KEY . ":CONTROL_NOW_UP_LOCATION:{$bike_no}");
            if (!$upLocation) {
                $upLocation = [
                    'lat' => 0,
                    'lng' => 0,
                    'spike' => '',
                    'time' => 0
                ];
            } else {
                $upLocation = BikeControl::decodeData($upLocation);
            }

            //定位包
            $location = self::$redis->get(BikeControl::CACHE_KEY . ":NOW_LOCATION:{$bike_no}");
            if (!$location) {
                $location = [
                    'lat' => 0,
                    'lng' => 0,
                    'mileage' => $location['mileage'],
                    'time' => 0
                ];
            } else {
                $location = BikeControl::decodeData($location);
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
            Log::error($exception->getMessage());
            return false;
        }
    }

    public function statisticalRideOrder()
    {
        $rides = self::$redis->keys(self::REDIS_RIDE_BIKE_ORDERS_TAG . "*");
        $nums = count($rides);
        $r_nums = self::$redis->get(self::REDIS_RIDE_BIKE_ORDERS_COUNT_TAG) ?? 0;
        if ($r_nums < $nums) {
            self::$redis->set(self::REDIS_RIDE_BIKE_ORDERS_COUNT_TAG, $nums);
        }
    }
}
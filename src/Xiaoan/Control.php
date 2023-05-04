<?php

namespace ZhMead\XmnkBikeControl\Xiaoan;

use GatewayClient\Gateway;
use ZhMead\XmnkBikeControl\Common\ControlInterface;
use ZhMead\XmnkBikeControl\Common\Maps\BaseMap;
use ZhMead\XmnkBikeControl\Xiaoan\Maps\CmdMap;
use ZhMead\XmnkBikeControl\Xiaoan\Maps\VideoMap;

class Control implements ControlInterface
{
    //分割符
    const SPLIT_TAG = '';
    const START_TAG = 'aa55';
    const CMD_WILD = '00';

    private static $registerAddress = '';
    protected static $isSync = false;
    protected static $userTypeTag = 'C';
    protected static $redis = false;
    protected static $bikeStatusSync = false;
    protected static $isDev = false;

    public function __construct($registerAddress, $bikeStatusSync, $isSync = false, $userTypeTag = 'C', $isDev = false)
    {
        self::$registerAddress = $registerAddress;
        self::$isSync = $isSync;
        self::$userTypeTag = $userTypeTag;
        self::$bikeStatusSync = $bikeStatusSync;
        self::$isDev = $isDev;
    }

    /**
     * 寻铃
     * @param $box_no
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function bell($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_CONTROL_VOICE_BROADCAST;
        $param = [
            'idx' => VideoMap::CAR_SEARCH_SOUND
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 开锁
     * @param $box_no
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function openLock($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_STARTORSTOP_VEHICLE;
        $param = [
            'acc' => 1
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 关锁
     * @param $box_no
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function closeLock($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_ANTITHEFT_SWITCH;
        $param = [
            'defend' => 1,
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 临时关锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function temporaryCloseLock($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_ANTITHEFT_SWITCH;
        $param = [
            'defend' => 1,
            'force' => 1
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 临时开锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function temporaryOpnLock($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_STARTORSTOP_VEHICLE;
        $param = [
            'acc' => 1
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 打开电池锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function openBatteryLock($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_OPEN_BATTERY_COMPARTMENT_LOCK;
        $param = [];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 关闭电池锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function closeBatteryLock($box_no, $isSync = -1)
    {
        return true;
    }

    /**
     * 超出骑行区域播放音乐
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function outAreaPlayVideo($box_no, $isSync = -1)
    {
        return $this->playVideo($box_no, VideoMap::NEAR_SERVICE_AREA_SOUND, $isSync);
    }

    /**
     * 播放语音
     * @param $box_no
     * @param $video_cmd
     * @return bool
     * User: Mead
     */
    public function playVideo($box_no, $video_cmd, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_CONTROL_VOICE_BROADCAST;
        $param = [
            'idx' => $video_cmd
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 超出骑行区域失能
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function outAreaLoseElectric($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_STARTORSTOP_VEHICLE;
        $param = [
            'acc' => 0
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 超出区域后返回骑行区域加电
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function outAreaGetElectric($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_STARTORSTOP_VEHICLE;
        $param = [
            'acc' => 1
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 查询车的配置
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function selectBoxSetting($box_no, $setting = [], $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_OBTAIN_CONTROLLER_DATA;
        $param = [];
        return $this->send($box_no, $cmd, $param, true);
    }

    /**
     * 查询车的服务器的地址
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function selectBoxServerUrl($box_no, $setting = [], $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_QUERY_SERVER_ADDRESS;
        $param = [];
        return $this->send($box_no, $cmd, $param, true);
    }

    /**
     * 配置服务器的地址
     * @param $box_no
     * @param $setting
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function setBoxServerUrl($box_no, $server = '', $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_MODIFY_SERVER_ADDRESS;
        $param = [
            'server' => $server
        ];
        return $this->send($box_no, $cmd, $param, true);
    }

    /**
     * 查询车的状态
     * @param $box_no
     * @return
     * User: Mead
     *??
     */
    public function selectBikeStatus($box_no, $setting = [], $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_QUERY_DEVICE_STATUS_INFO;
        $param = [];
        return self::send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 远程重启中控
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function rebootBox($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_DEVICE_RESTART;
        $param = [];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 立即定位
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function nowBikeLocation($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_QUERY_DEVICE_STATUS_INFO;
        $param = [];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 立即上传电池信息
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function nowBikeBatteryMSG($box_no, $isSoc = 0, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_QUERY_DEVICE_STATUS_INFO;
        if ($isSoc) {
            $cmd = CmdMap::COMMAND_OBTAIN_BMS_REALTIME_DATA;
        }
//        $cmd = CmdMap::COMMAND_OBTAIN_BMS_FIXED_DATA;
        $param = [];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 参数配置
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function setBoxSetting($box_no, $setting = [], $is_result = false, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_QUERY_DEVICE_STATUS_INFO;

        $param[] = [];
        if (array_key_exists('freq', $setting)) {
            $param['freq_move'] = $setting['freq'];
        }

        if (array_key_exists('server', $setting)) {
            $p['server'] = $setting['server'];
            self::send($box_no, CmdMap::COMMAND_MODIFY_SERVER_ADDRESS, $p);
        }

        if (array_key_exists('maxecuspeed', $setting)) {
            $index = 7;
            $p2['speed'] = 100 - ($index - $setting['maxecuspeed']) * 5;
            self::send($box_no, CmdMap::COMMAND_SET_CONTROLLER_SPEED_LIMIT, $p2);
        }

        if (count($param)) {
            return self::send($box_no, $cmd, $param);
        }
        return true;
    }

    /**
     * 远程撤防
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function cheFang($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_ANTITHEFT_SWITCH;
        $param = [
            'defend' => 0
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 远程加锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function addLock($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_ANTITHEFT_SWITCH;
        $param = [
            'defend' => 1
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 打开头盔
     * @param $box_no
     * @return bool
     * Author: Mead
     */
    public function openHelmetLock($box_no, $isSync = -1)
    {
        return false;
    }

    /**
     * 发送数据包
     * @param $box_no
     * @param $cmd
     * @param $param
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    private function send($box_no, $cmd, $param, $isSync = -1)
    {
        $msg_id = self::getRandHex();
        $msg = self::encode($cmd, $param, $msg_id);

        Gateway::$registerAddress = self::$registerAddress;
        if (!Gateway::isUidOnline($box_no)) return false;

        if ($isSync === -1) {
            $isSync = self::$isSync;
        } else {
            $isSync = (bool)$isSync;
        }

        try {
            if (self::$isDev) var_dump($msg);
            Gateway::sendToUid($box_no, hex2bin($msg));
            if ($isSync) {
                //是否获取相应
//                $redis = self::$redis;
                $response = false;

                for ($i = 0; $i <= 30; $i++) {
                    sleep(1);
                    if (self::$isDev) var_dump($i . "==>cmd:{$box_no}:{$msg_id}");

//                    $data = $redis->get(BaseMap::CACHE_KEY . ":cmd:{$box_no}:{$msg_id}");
                    $data = self::$bikeStatusSync->getBikeBoxInfo(":cmd:{$box_no}:{$msg_id}");
                    if ($data) {
                        $response = $this->decodeData($data);
                        break;
                    }
                    if (in_array($i, [5, 10, 15, 20])) {
                        //重试一次
                        Gateway::sendToUid($box_no, hex2bin($msg));
                    }
                }
                return $response;
            }
        } catch (\Exception $exception) {
            throw new \Exception('服务连接失败');
        }

        return true;
    }

    /**
     * 编码
     * @param $data
     * @return string
     * User: Mead
     */
    private function encode($cmd, $param = [], $randHex = false)
    {
        $body = [
            'c' => $cmd,
            'param' => $param
        ];

        $bodyStr = json_encode($body, true);
        $bodyArrHex = $this->str2arr(bin2hex($bodyStr));
        $bodyNumsHex = $this->byNumGetDataLength(count($bodyArrHex));

        if ($randHex === false) {
            $randHex = $this->getRandHex();
        }

        $msg = [
            self::START_TAG,
            self::CMD_WILD,
            $randHex,
            $bodyNumsHex,
            implode('', $bodyArrHex)
        ];

        $msg = implode('', $msg);
        return $msg;
    }

    /**
     * 格式数组
     * @param $arr
     * @return array
     * User: Mead
     */
    private function str2arr($str)
    {
        return str_split($str, 2);
    }

    /**
     * 获取数据包的长度
     * @param $num
     * @return string
     * User: Mead
     */
    private function byNumGetDataLength($num)
    {
        $length = dechex($num);
        return str_pad($length, 4, '0', STR_PAD_LEFT);
    }

    /**
     * 获取数据包的随机码
     * @param $num
     * @return string
     * User: Mead
     */
    private function getRandHex()
    {
        $length = dechex(rand(0, 100));
        return str_pad($length, 2, '0', STR_PAD_LEFT);
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

    /**
     * 删除缓存
     * @param $box_no
     * @param $type
     * User: Mead
     */
    public static function delRedisCache($box_no, $types)
    {
        $cacheNames = [];
        if (is_array($types)) {
            foreach ($types as $type) {
                $cacheNames[] = "cache:min:{$type}:{$box_no}";
            }
        } else {
            $cacheNames[] = "cache:min:{$types}:{$box_no}";
        }
        if (!count($cacheNames)) return false;
//        self::$redis->del($cacheNames);
        self::$bikeStatusSync->delKeys($cacheNames);
    }
}

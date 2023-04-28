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
    const SPLIT_TAG = 'aa55';
    const START_TAG = 'AA AA';
    const CMD_WILD = '00';

    private static $registerAddress = '';
    protected static $isSync = false;
    protected static $userTypeTag = 'C';
    protected static $redis = false;
    protected static $isDev = false;

    public function __construct($registerAddress, $redis, $isSync = false, $userTypeTag = 'C', $isDev = false)
    {
        self::$registerAddress = $registerAddress;
        self::$isSync = $isSync;
        self::$userTypeTag = $userTypeTag;
        self::$redis = $redis;
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
            'force' => 1
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
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
                $redis = self::$redis;
                $response = false;

                for ($i = 0; $i <= 30; $i++) {
                    sleep(1);
                    if (self::$isDev) var_dump($i);
                    $data = $redis->get(BaseMap::CACHE_KEY . "cmd:{$box_no}:{$msg_id}");
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
            return true;
        } catch (\Exception $exception) {
            throw new \Exception('服务连接失败');
        }

        $response = self::getBikeResponse($box_no, $msg_id);
        return $response;
    }

    /**
     * 效验编码
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
            self::SPLIT_TAG,
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
        self::$redis->del($cacheNames);
    }
}

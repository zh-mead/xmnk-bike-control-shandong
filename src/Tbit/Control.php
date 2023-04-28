<?php

namespace ZhMead\XmnkBikeControl\Tbit;

use GatewayClient\Gateway;
use ZhMead\XmnkBikeControl\Common\ControlInterface;
use ZhMead\XmnkBikeControl\Common\Maps\BaseMap;
use ZhMead\XmnkBikeControl\Tbit\Maps\CmdMap;
use ZhMead\XmnkBikeControl\Tbit\Maps\VideoMap;

class Control implements ControlInterface
{
    //分割符
    const SPLIT_TAG = 'bbbb';
    const START_TAG = 'AA AA';

    private static $registerAddress = '';
    protected $isSync = false;
    protected $userTypeTag = 'C';

    public function __construct($registerAddress, $isSync = false, $userTypeTag = 'C')
    {
        self::$registerAddress = $registerAddress;
        $this->isSync = $isSync;
        $this->userTypeTag = $userTypeTag;
    }

    /**
     * 寻车响铃
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function bell($box_no)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_REMOTE_FIND_BIKE);
        $str = $this->makeSendMsg(CmdMap::CONTROL_REMOTE_FIND_BIKE, $msg_id);
        return $this->send($box_no, $str);
    }

    /**
     * 开车
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function openLock($box_no, $sync = false)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_REMOTE_UNLOCK);
        $str = $this->makeSendMsg(CmdMap::CONTROL_REMOTE_UNLOCK, $msg_id);
        $this->send($box_no, $str);
        return true;
    }

    /**
     * 关锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function closeLock($box_no)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_REMOTE_CLOSE_LOCK);
        $str = $this->makeSendMsg(CmdMap::CONTROL_REMOTE_CLOSE_LOCK, $msg_id);
        $this->send($box_no, $str);
        return true;
    }

    /**
     * 临时关锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function temporaryCloseLock($box_no)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_REMOTE_TEMPORARY_CLOSE_LOCK);
        $str = $this->makeSendMsg(CmdMap::CONTROL_REMOTE_TEMPORARY_CLOSE_LOCK, $msg_id);
        $this->send($box_no, $str);
        return true;
    }

    /**
     * 临时开锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function temporaryOpnLock($box_no)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_REMOTE_TEMPORARY_UNLOCK);
        $str = $this->makeSendMsg(CmdMap::CONTROL_REMOTE_TEMPORARY_UNLOCK, $msg_id);
        $this->send($box_no, $str);
        return true;
    }

    /**
     * 寻车响铃
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function bellBike($box_no)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_REMOTE_FIND_BIKE);
        $str = $this->makeSendMsg(CmdMap::CONTROL_REMOTE_FIND_BIKE, $msg_id);
        return $this->send($box_no, $str);
    }

    /**
     * 打开电池锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function openBatteryLock($box_no)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_REMOTE_OPEN_BATTERY_LOCK);
        $str = $this->makeSendMsg(CmdMap::CONTROL_REMOTE_OPEN_BATTERY_LOCK, $msg_id);
        return $this->send($box_no, $str);
    }

    /**
     * 关闭电池锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function closeBatteryLock($box_no)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_REMOTE_CLOSE_BATTERY_LOCK);
        $str = $this->makeSendMsg(CmdMap::CONTROL_REMOTE_CLOSE_BATTERY_LOCK, $msg_id);
        return $this->send($box_no, $str);
    }

    /**
     * 超出骑行区域播放音乐
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function outAreaPlayVideo($box_no)
    {
        return $this->playVideo($box_no, VideoMap::VIDEO_OUT_AREA);
    }

    /**
     * 播放语音
     * @param $box_no
     * @param $video_cmd
     * @return bool
     * User: Mead
     */
    public function playVideo($box_no, $video_cmd)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', $video_cmd);
        $str = $this->makeSendMsg($video_cmd, $msg_id, CmdMap::CMD_REMOTE_VOICE);
        return $this->send($box_no, $str);
    }

    /**
     * 超出骑行区域失能
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function outAreaLoseElectric($box_no)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_OUT_AREA_LOST_ELECTRIC);
        $str = $this->makeSendMsg(CmdMap::CONTROL_OUT_AREA_LOST_ELECTRIC, $msg_id, CmdMap::CMD_REMOTE_CONTROL);
        return $this->send($box_no, $str);
    }

    /**
     * 超出区域后返回骑行区域加电
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function outAreaGetElectric($box_no)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_OUT_AREA_OPEN_ELECTRIC);
        $str = $this->makeSendMsg(CmdMap::CONTROL_OUT_AREA_OPEN_ELECTRIC, $msg_id, CmdMap::CMD_REMOTE_CONTROL);
        return $this->send($box_no, $str);
    }

    /**
     * 查询车的配置
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function selectBoxSetting($box_no)
    {
        $select = ['TID', 'AUTOLOCKEVENT', 'BLEKG', 'BATMANUFACTURE', 'DFTBLEBONDKEY', 'BATSN', 'DOMAIN', 'BLEKG', 'PULSE', 'VIBFILTERREMINDT', 'FREQ'];
//        $select = ['SOFTVERSION', 'TID', 'BLEKG', 'LOGIN', 'DFTBLEENCKEY', 'DFTBLEENCKEY', 'DOMAIN', 'BLEKG', 'PULSE', 'VIBFILTERREMINDT', 'FREQ'];
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CMD_REMOTE_SELECT);
        $str = $this->makeSendMsg($select, $msg_id, CmdMap::CMD_REMOTE_SELECT, false);
        return $this->send($box_no, $str);
    }

    /**
     * 查询车的状态
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function selectBikeStatus($box_no)
    {
        $select = ['DEVICESTATUS', 'PHASESTATUS'];
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CMD_REMOTE_SELECT);
        $str = $this->makeSendMsg($select, $msg_id, CmdMap::CMD_REMOTE_SELECT, false);
        return $this->send($box_no, $str);
    }

    /**
     * 远程重启中控
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function rebootBox($box_no)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_REMOTE_REBOOT_SYSTEM);
        $str = $this->makeSendMsg(CmdMap::CONTROL_REMOTE_REBOOT_SYSTEM, $msg_id);
        return $this->send($box_no, $str);
    }

    /**
     * 立即定位
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function nowBikeLocation($box_no)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_REMOTE_LOCATION);
        //删除ridis位置缓存
        $this->delRedisCache($box_no, 'update_bike_location');
        $str = $this->makeSendMsg(CmdMap::CONTROL_REMOTE_LOCATION, $msg_id);
        return $this->send($box_no, $str);
    }

    /**
     * 融合定位包
     * @param $box_no
     * @return bool
     * Author: Mead
     */
    public function nowBikeUpLocation($box_no, $sync = false)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_NOW_UP_LOCATION);
        //删除ridis位置缓存
        $str = $this->makeSendMsg(CmdMap::CONTROL_NOW_UP_LOCATION, $msg_id);
        if ($sync) {
            return $this->sendSync($box_no, $str, "CONTROL_NOW_UP_LOCATION:{$box_no}");
        } else {
            return $this->send($box_no, $str);
        }
    }

    /**
     * 立即上传电池信息
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function nowBikeBatteryMSG($box_no)
    {
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CONTROL_GET_BATTERY_INFO);
        $str = $this->makeSendMsg(CmdMap::CONTROL_GET_BATTERY_INFO, $msg_id);
        return $this->send($box_no, $str);
    }

    /**
     * 参数配置
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function setBoxSetting($box_no)
    {
        $select = ['PULSE=120', 'FREQ=15', 'VIBFILTERREMINDT=20', 'DFTBLEBONDKEY=NULL', 'BLEKG=1'];
        $msg_id = $this->makeMsgId($box_no, 'C', CmdMap::CMD_REMOTE_CONFIG);
        $str = $this->makeSendMsg($select, $msg_id, CmdMap::CMD_REMOTE_CONFIG, false);
        return $this->send($box_no, $str);
    }

    /**
     * 发送数据
     * @param $box_no
     * @param $msg
     * @return bool
     * User: Mead
     */
    private static function send($box_no, $msg)
    {
        try {
            Gateway::$registerAddress = self::$registerAddress;
//            if (!Gateway::isUidOnline($box_no)) return false;
            var_dump('tbit:' . self::$registerAddress);
            var_dump('tbit:' . $msg);
            Gateway::sendToUid($box_no, hex2bin($msg));
            return true;
        } catch (\Exception $exception) {
            throw new \Exception('服务连接失败');
        }
    }

    /**
     * 发送同步命令
     * @param $box_no
     * @param $msg
     * @return bool
     * Author: Mead
     */
    private function sendSync($box_no, $msg, $msg_id)
    {
        Gateway::$registerAddress = self::$registerAddress;
        if (!Gateway::isUidOnline($box_no)) return false;
        Gateway::sendToUid($box_no, hex2bin($msg));

        $redis = \Redis::connection();
        $response = false;

        for ($i = 0; $i <= 30; $i++) {
            sleep(1);
            $data = $redis->get(BaseMap::CACHE_KEY . ':' . $msg_id);

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

    /**
     * 解析车辆返回数据
     * @param $data
     * @return mixed
     */
    public function decodeData($data, $decode = true)
    {
        return json_decode($data, true);
    }

    /**
     * 生成 msg_id
     * @param $box_no
     * @param $type
     * @param $cmd
     * @return string
     * User: Mead
     */
    private static function makeMsgId($box_no, $type, $cmd)
    {
        $msg = [
            'no' => $box_no,
            'time' => time(),
            'type' => $type,
            'cmd' => $cmd,
        ];
        $msg_id = self::SPLIT_TAG . bin2hex(implode(',', $msg));
        return $msg_id;
    }

    /**
     * 组装命令
     * @param $cmd
     * @param $msgID
     * @return string
     * User: Mead
     */
    public function makeSendMsg($controller_cmd, $msgID, $cmd = CmdMap::CMD_REMOTE_CONTROL, $is_hex = true)
    {
        if (!$is_hex) {
            $controller_cmd = bin2hex((implode(';', $controller_cmd) . ';FUJIA'));
        }
        $body = [
            "{$controller_cmd}",
            "{$msgID}"
        ];
        $body = $this->arr2arr($body);

        return $this->encode($body, $cmd);
    }

    /**
     * 效验编码
     * @param $data
     * @return string
     * User: Mead
     */
    private function encode($data, $cmd)
    {
        $num = 12;
        $num += count($data);
        $data_length = $this->byNumGetDataLength($num);
        $header = [
            $data_length,
            $this->getSoftwareVersion(),
            $cmd,
            $this->getPipelineNumber(),
            '00',
            '00',
            '00'
        ];

        $response = $this->arr2str($header, $data);
        $response .= $this->crc16(explode(' ', $response));

        return $this->format(self::START_TAG . $response);
    }

    /**
     * 获取数据包的长度
     * @param $num
     * @return String
     * User: Mead
     */
    private static function byNumGetDataLength($num)
    {
        $length = dechex($num);
        return str_pad($length, 4, '0', STR_PAD_LEFT);
    }

    /**
     * 软件版本号
     * @return string
     * User: Mead
     */
    private static function getSoftwareVersion()
    {
        return '04';
    }

    /**
     * 获取流水号
     * @return int
     * User: Mead
     */
    private static function getPipelineNumber()
    {
        return '01';
    }

    /**
     * 删除缓存
     * @param $box_no
     * @param $type
     * User: Mead
     */
    public function delRedisCache($box_no, $types)
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
        Redis::connection()->del($cacheNames);
    }

    /**
     * 格式数组
     * @param $arr
     * @return array
     * User: Mead
     */
    private static function arr2arr($arr)
    {
        $arr = implode('', $arr);
        return str_split(str_replace(' ', '', $arr), 2);
    }

    /**
     * 数组转字符
     * @return string
     * User: Mead
     */
    private static function arr2str()
    {
        $args = func_get_args();
        $arr = '';
        foreach ($args as $arg) {
            $arr .= implode('', $arg);
        }
        return implode(' ', str_split($arr, 2));
    }

    /**
     * 格式字符
     * @param $str
     * @return string
     * User: Mead
     */
    private static function format($str)
    {
        return strtoupper(implode('', str_split(str_replace(' ', '', $str), 2)));
    }

    private static $crc_table = [
        0x0000, 0x1189, 0x2312, 0x329B, 0x4624, 0x57AD,
        0x6536, 0x74BF, 0x8C48, 0x9DC1, 0xAF5A, 0xBED3,
        0xCA6C, 0xDBE5, 0xE97E, 0xF8F7, 0x1081, 0x0108,
        0x3393, 0x221A, 0x56A5, 0x472C, 0x75B7, 0x643E,
        0x9CC9, 0x8D40, 0xBFDB, 0xAE52, 0xDAED, 0xCB64,
        0xF9FF, 0xE876, 0x2102, 0x308B, 0x0210, 0x1399,
        0x6726, 0x76AF, 0x4434, 0x55BD, 0xAD4A, 0xBCC3,
        0x8E58, 0x9FD1, 0xEB6E, 0xFAE7, 0xC87C, 0xD9F5,
        0x3183, 0x200A, 0x1291, 0x0318, 0x77A7, 0x662E,
        0x54B5, 0x453C, 0xBDCB, 0xAC42, 0x9ED9, 0x8F50,
        0xFBEF, 0xEA66, 0xD8FD, 0xC974, 0x4204, 0x538D,
        0x6116, 0x709F, 0x0420, 0x15A9, 0x2732, 0x36BB,
        0xCE4C, 0xDFC5, 0xED5E, 0xFCD7, 0x8868, 0x99E1,
        0xAB7A, 0xBAF3, 0x5285, 0x430C, 0x7197, 0x601E,
        0x14A1, 0x0528, 0x37B3, 0x263A, 0xDECD, 0xCF44,
        0xFDDF, 0xEC56, 0x98E9, 0x8960, 0xBBFB, 0xAA72,
        0x6306, 0x728F, 0x4014, 0x519D, 0x2522, 0x34AB,
        0x0630, 0x17B9, 0xEF4E, 0xFEC7, 0xCC5C, 0xDDD5,
        0xA96A, 0xB8E3, 0x8A78, 0x9BF1, 0x7387, 0x620E,
        0x5095, 0x411C, 0x35A3, 0x242A, 0x16B1, 0x0738,
        0xFFCF, 0xEE46, 0xDCDD, 0xCD54, 0xB9EB, 0xA862,
        0x9AF9, 0x8B70, 0x8408, 0x9581, 0xA71A, 0xB693,
        0xC22C, 0xD3A5, 0xE13E, 0xF0B7, 0x0840, 0x19C9,
        0x2B52, 0x3ADB, 0x4E64, 0x5FED, 0x6D76, 0x7CFF,
        0x9489, 0x8500, 0xB79B, 0xA612, 0xD2AD, 0xC324,
        0xF1BF, 0xE036, 0x18C1, 0x0948, 0x3BD3, 0x2A5A,
        0x5EE5, 0x4F6C, 0x7DF7, 0x6C7E, 0xA50A, 0xB483,
        0x8618, 0x9791, 0xE32E, 0xF2A7, 0xC03C, 0xD1B5,
        0x2942, 0x38CB, 0x0A50, 0x1BD9, 0x6F66, 0x7EEF,
        0x4C74, 0x5DFD, 0xB58B, 0xA402, 0x9699, 0x8710,
        0xF3AF, 0xE226, 0xD0BD, 0xC134, 0x39C3, 0x284A,
        0x1AD1, 0x0B58, 0x7FE7, 0x6E6E, 0x5CF5, 0x4D7C,
        0xC60C, 0xD785, 0xE51E, 0xF497, 0x8028, 0x91A1,
        0xA33A, 0xB2B3, 0x4A44, 0x5BCD, 0x6956, 0x78DF,
        0x0C60, 0x1DE9, 0x2F72, 0x3EFB, 0xD68D, 0xC704,
        0xF59F, 0xE416, 0x90A9, 0x8120, 0xB3BB, 0xA232,
        0x5AC5, 0x4B4C, 0x79D7, 0x685E, 0x1CE1, 0x0D68,
        0x3FF3, 0x2E7A, 0xE70E, 0xF687, 0xC41C, 0xD595,
        0xA12A, 0xB0A3, 0x8238, 0x93B1, 0x6B46, 0x7ACF,
        0x4854, 0x59DD, 0x2D62, 0x3CEB, 0x0E70, 0x1FF9,
        0xF78F, 0xE606, 0xD49D, 0xC514, 0xB1AB, 0xA022,
        0x92B9, 0x8330, 0x7BC7, 0x6A4E, 0x58D5, 0x495C,
        0x3DE3, 0x2C6A, 0x1EF1, 0x0F78,
    ];

    /**
     * 生成校检码
     * @param $data
     * @return string
     * User: Mead
     */
    private static function crc16($data)
    {
        $crc = 0xFFFF;

        foreach ($data as $d) {
            $d = hexdec($d);
            $crc = self::$crc_table[($d ^ $crc) & 0xFF] ^ ($crc >> 8 & 0xFF);
        }

        $crc = $crc ^ 0xFFFF;
        $crc = $crc & 0xFFFF;

        return str_pad(dechex($crc), 4, '0', STR_PAD_LEFT);
    }
}
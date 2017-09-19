<?php

namespace common\models\message;

use Yii;
use yii\base\Component;

class Sms extends Component
{
    const SMS_SEND_GATEWAY = 'http://sms-api.luosimao.com/v1/send.json';
    const SMS_BATCH_SEND_GATEWAY = 'http://sms-api.luosimao.com/v1/send_batch.json';

    const SMS_SIGN = '公司标志信息';

    public $apikey;

    /**
     * 发送短信
     *
     * @param string $phone
     * @param string $message
     * @param array $errors
     * @return bool
     */
    public function send($phone, $message, &$errors = [])
    {
        file_put_contents('/tmp/sms.wangba.log', date('Y-m-d H:i:s') . PHP_EOL . $phone . PHP_EOL . $message . PHP_EOL, FILE_APPEND);
        if (YII_ENV_DEV) {
            return true;
        }

        $data = [
            'mobile' => $phone,
            'message' => $message . '【' . self::SMS_SIGN . '】',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::SMS_SEND_GATEWAY);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-' . $this->apikey);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res, true);

        if (is_array($res) && array_key_exists('error', $res) && $res['error'] == 0) {
            return true;
        }

        $errors = $res['msg'];
        return false;
    }

    /**
     * 批量发送短信
     *
     * @param string $phones
     * @param string $message
     * @param array $errors
     * @return bool
     */
    public function batchSend($phones, $message, &$errors = [])
    {
        file_put_contents('/tmp/sms.wangba.log', date('Y-m-d H:i:s') . PHP_EOL . $phones . PHP_EOL . $message . PHP_EOL, FILE_APPEND);
        if (YII_ENV_DEV) {
            return true;
        }

        $data = [
            'mobile_list' => $phones,
            'message' => $message . '【' . self::SMS_SIGN . '】',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::SMS_BATCH_SEND_GATEWAY);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-' . $this->apikey);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res, true);

        if (is_array($res) && array_key_exists('error', $res) && $res['error'] == 0) {
            return true;
        }

        $errors = $res['msg'];
        return false;
    }
}
<?php

namespace Capital\Oursms;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;


class Send
{

    public static function id()
    {
        return config('oursms.our_sms_id');
    }

    public static function key()
    {
        return config('oursms.our_sms_key');
    }



    public static function oneSms($phone, $message)
    {
        $format_phone = Self::formatPhone($phone);
        if($format_phone == false) {
            return response()->json(["message" => "incorrect phone format"], 401);
        }
        $response = Http::post('https://oursms.app/api​/v1​/SMS​/Add​/SendOneSms', [
            'userId' => self::id(),
            'key' => self::id(),
            'phoneNumber' => $phone,
            'Message' => $message,
        ]);
        return $response;
    }


    public static function otp($phone, $expire_minutes)
    {
        $format_phone = Self::formatPhone($phone);
        if($format_phone == false) {
            return response()->json(["message" => "incorrect phone format"], 401);
        }
        $otp = rand('1000', '9999');
        $expire_at = now()->addMinutes($expire_minutes);
        Cache::put($phone, $otp, $expire_at);
        $response = Http::post('https://oursms.app/api​/v1​/SMS​/Add​/SendOtpSms', [
            'userId' => self::id(),
            'key' => self::id(),
            'phoneNumber' => $phone,
            'otp' => $otp,
        ]);
        return $response;
    }


    public static function checkOTP($phone, $otp)
    {
        $cache = Cache::get($phone);
        if (!is_null($cache)) {
            if ($cache == $otp) {
                $response = [
                    'status' => false,
                    'message' => 'OTP timeout',
                    'code' => 401,
                ];
            } else {
                $response = [
                    'status' => true,
                    'message' => 'otp was matched',
                    'code' => 200,
                ];
            }
        } else {
            $response = [
                'status' => false,
                'message' => 'OTP timeout',
                'code' => 401,
            ];
        }
        return response()->json($response, $response['code']);
    }


    public static function status($messageId)
    {
        $response = Http::get('https://oursms.app/api/v1/SMS/Get/GetStatus/'. $messageId);
        return $response;
    }



    public static function formatPhone($phone)
    {
        $iq_first_number = array('77', '78', '75');
        $iq_first_number_with_key = array('96477', '96478', '96475');
        $length = strlen($phone);
        if ($length == 10) {
            if (in_array(substr($phone, 0, 2), $iq_first_number)) {
                $phone_number = '964' . $phone;
            }
        } elseif ($length == 11) {
            $phone_number = '964' . ltrim($phone, $phone[0]);
        } elseif ($length == 13) {
            if (in_array(substr($phone, 0, 5), $iq_first_number_with_key)) {
                $phone_number = $phone;
            }
        }else{
            return false;
        }

        if (isset($phone_number)) {
            return $phone_number;
        } else {
            return false;
        }

        return $phone;
    }
}

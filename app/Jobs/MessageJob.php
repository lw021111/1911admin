<?php

namespace App\Jobs;

use App\models\MsgModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $msg;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( MsgModel $msg_model )
    {
        $this -> msg = $msg_model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle( )
    {
        $msg_data = $this -> msg -> toArray();

        $phone = $msg_data['phone'];

        if( $this -> _sendSms( $phone , $msg_data['rand_code'] ) )
        {
            $this -> msg -> is_send = 1;
            if( $this -> msg -> save() ) {
                return true;
            }else{
                throw new \Exception('短信发送成功，短信表修改失败，手机号：' .$phone.',id='.$msg_data['id'] );
            }
        }else{
            throw new \Exception('短信发送失败，手机号：' .$phone.',id='.$msg_data['id'] );
        }

    }

    /**
     * 发送短信
     */
    private function _sendSms( $phone , $code )
    {
        return true;
        $host = "http://dingxin.market.alicloudapi.com";
        $path = "/dx/sendSms";
        $method = "POST";
        $appcode = "7fb082fd76844276b4fb785add7807ab";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "mobile=".$phone."&param=code%3A".$code."&tpl_id=TP1711063";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false );
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        $send = json_decode( curl_exec($curl) , true );

        if( $send['return_code'] == '00000' )
        {
            return true;
        }else{
            return false;
        }

    }
}

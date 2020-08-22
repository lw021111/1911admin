<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\models\MsgModel;
use Illuminate\Http\Request;
use App\Jobs\MessageJob;
use Illuminate\Support\Facades\Redis;

class MsgController extends Controller
{
    //


    public function test( Request $request )
    {

        $phone = $request -> get('phone')??'';
        if( empty($phone) ){
            exit('手机号为空');
        }

        $model = new MsgModel();

        $model -> phone = $phone;
        $model -> rand_code = rand( 1000,9999);
        $model -> is_send = 0;
        $model -> status = 1;
        $model -> ctime = time();

        if( $model -> save() ){
            dd( MessageJob::dispatch($model ) -> onQueue('MsgQueue'));
        }
    }
}

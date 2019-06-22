<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\mode\User;
class RegController extends Controller
{
    /*
     * 注册视图
     * */
    public function reg()
    {
        return view('user.reg');
    }
    //发送短信
    public function code()
    {
        $user_tel = $_POST['tel'];
        $host = "http://dingxin.market.alicloudapi.com";
        $path = "/dx/sendSms";
        $method = "POST";
        $appcode = "81f4c93dd39c45ada67a72f93e01a57e";
        $headers = array();
        $rand  = mt_rand(11111,99999);
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "mobile=".$user_tel."&param=code%3A".$rand."&tpl_id=TP1711063";
        $bodys = "";
        $url = $host . $path . "?" . $querys;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);//CURLOPT_HEADER设置为True，可以获取响应的头信息
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $curl=json_decode(curl_exec($curl),true);

        if($curl['return_code'] ==00000){
            Session::put(['rand'=>$rand,'tel'=>$user_tel,'timeout'=>time()+300]);
            echo json_encode(['msg'=>'发送成功','code'=>1]);
        }else{
            echo json_encode(['msg'=>'发送失败','code'=>2]);die;
        }
    }

    public function regdo(Request $request)
    {
       $code = Session::get('rand');
       $tel = Session::get('tel');
       $timeout = Session::get('timeout');
       $user_name = $request-> input('user_name');
       $user_tel = $request-> input('user_tel');
       $user_pwd = $request-> input('user_pwd');
       $user_code = $request-> input('user_code');
       $one = User::where(['user_tel'=>$user_tel])->first();
       if(empty($user_name) || empty($user_tel) || empty($user_pwd) || empty($user_code))
       {
            $reponse = [
                'errno'=> '4000',
                'msg'=> '必填项不可为空'
            ];
            die(json_encode($reponse,JSON_UNESCAPED_UNICODE));
       }else{
           if($user_tel != $tel || $code != $user_code || time() - $timeout >300){
               $reponse = [
                   'errno'=> '4001',
                   'msg'=> '验证码失效请重新发送'
               ];
               die(json_encode($reponse,JSON_UNESCAPED_UNICODE));
           }else{
                if($one){
                    $reponse = [
                        'errno'=> '4003',
                        'msg'=> '手机号已存在'
                    ];
                    die(json_encode($reponse,JSON_UNESCAPED_UNICODE));
                }else{
                    //添加至数据库
                    $pass = password_hash($user_pwd,PASSWORD_DEFAULT);
                    $data = [
                        'user_name'=> $user_name,
                        'user_pwd'=> $pass,
                        'user_tel'=> $user_tel,
                        'user_code'=> $user_code

                    ];
                    $res = User::insertGetId($data);
                    if($res){
                        $reponse = [
                            'errno'=> 'ok',
                            'msg'=> '注册成功'
                        ];
                        die(json_encode($reponse,JSON_UNESCAPED_UNICODE));
                    }else{
                        $reponse = [
                            'errno'=> '4004',
                            'msg'=> '注册失败'
                        ];
                        die(json_encode($reponse,JSON_UNESCAPED_UNICODE));
                    }
                }
           }
       }

    }
}

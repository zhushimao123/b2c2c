<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    public function login()
    {
        return view('user.login');
    }
    public function LoginDO()
    {
        $user_name = $_POST['user_name'];
        $user_pwd = $_POST['user_pwd'];
    }
    //微信网页授权
    public function WxLogin()
    {
        //urlEncode 数据加密
       $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx48451c201710dbcd&redirect_uri='.urlEncode('http://test.mneddx.com/code').'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
       header('refresh:0;url='.$url);

    }
    //获取code
    public function getcode()
    {
        //code五分钟过期
        $key = 'code001';
        $code = $_GET['code'];
        $redis_key = Redis::get($key);
        if(!$redis_key){
            Redis::set($key,$code);
            Redis::expire($key,3600);
        }else{
            return $redis_key;
        }
        var_dump($redis_key);


    }
}

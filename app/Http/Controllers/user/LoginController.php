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
            $this-> code($code);
        }else{
            $this-> code($redis_key);
        }
    }
    //通过code获取access_token
    public function code($code)
    {
        var_dump($code);
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx48451c201710dbcd&secret=f583f90f3aed8ec33ae6dd30eceebe5f&code='.$code.'&grant_type=authorization_code';
        $json_data = file_get_contents($url);
        var_dump($json_data);die;
        $arr_data = json_decode($json_data,true);
        var_dump($arr_data);die;
        $this-> accesstoken($arr_data['access_token']);
    }
    //通过access——token获取用户信息
    public function accesstoken($token)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$token.'&openid=wx48451c201710dbcd&lang=zh_CN';
        $json_data = file_get_contents($url);
        $arr_data = json_decode($json_data,true);
        echo '<pre>';print_r($arr_data);echo "<pre>";
    }
}

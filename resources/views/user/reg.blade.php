@extends('app')

@section('title', '球球练习-注册')

@section('sidebar')
    @parent
@endsection

@section('content')
    <!-- register -->
    <div class="pages section">
        <div class="container">
            <div class="pages-head">
                <h3>REGISTER</h3>
            </div>
            <div class="register">
                <div class="row">
                    <form class="col s12">
                        <div class="input-field">
                            <input type="text" class="validate" placeholder="NAME" required id="user_name">
                        </div>
                        <div class="input-field">
                            <input type="password" placeholder="PASSWORD" class="validate" required id="user_pwd">
                        </div>
                        <div class="input-field">
                            <input type="tel" placeholder="tel" class="validate tel" required  id="user_tel">
                            <a class="btn button-default" id="code">点击获取验证码</a>
                        </div>
                        <div class="input-field">
                            <input type="text" placeholder="io code regiht" class="validate" required id="user_code">
                        </div>
                        <div class="btn button-default btns">注册</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end register -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script>
        $(function () {
           $('#code').click(function () {
               var tel = $('.tel').val();
               var reg = /^1[3,4,5,7,8]\d{9}$/;
               if(!reg.test(tel)){
                   alert('手机号格式不正确');
                   return false;
               }
              $('#code').text(60+'s');
              _time = setInterval(secondLess,1000);
               function secondLess(){
                   var second = parseInt($('#code').text());
                   if(second == 0){
                       $('#code').text('获取验证码');
                       clearInterval(_time); //清空倒计时
                   }else{
                       second = parseInt(second-1);
                       $('#code').text(second+'s');
                   }
               }
            //发送
               $.ajax({
                    url:'TelCode',
                    type:'post',
                    data:{tel:tel},
                    dataType:'json',
                    success:function (res) {
                        if(res.code =='1'){
                            alert('已发送，请注意查看');
                        }
                    }
               });

           })
            //注册
            $('.btns').click(function () {
                var user_name = $('#user_name').val();
                var user_pwd = $('#user_pwd').val();
                var user_tel = $('#user_tel').val();
                var user_code = $('#user_code').val();
                $.ajax({
                    url:'RegDo',
                    type:'post',
                    data:{user_name:user_name,user_pwd:user_pwd,user_code:user_code,user_tel:user_tel},
                    success:function (res) {
                       if(res.errno == '4000'){
                           alert('必填项不可为空');
                       }else if(res.errno =='4001'){
                           alert('验证码失效请重新发送');
                       }else if(res.errno =='4003'){
                           alert('手机号已存在');
                       }else if(res.errno =='4004'){
                           alert('注册失败');
                       }else if(res.errno =='ok'){
                           alert('注册成功');
                           location.href='/login';
                       }
                    }
                });
            });
        })
    </script>
@endsection
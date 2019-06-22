@extends('app')

@section('title', '球球练习-注册')

@section('sidebar')
    @parent
@endsection

@section('content')
<!-- login -->
<div class="pages section">
    <div class="container">
        <div class="pages-head">
            <h3>LOGIN</h3>
        </div>
        <div class="login">
            <div class="row">
                <form class="col s12">
                    <div class="input-field">
                        <input type="text" class="validate" placeholder="USERNAME" required name="user_name" id="user_name">
                    </div>
                    <div class="input-field">
                        <input type="password" class="validate" placeholder="PASSWORD" required name="user_pwd" id="user_pwd">
                    </div>
                    <a href=""><h6>Forgot Password ?</h6></a>
                    <a class="btn button-default btns">LOGIN</a>
                    <a href="/wxlogin"><img src="/img/37c58PICuME.png" alt="" width="50" height="50"></a>
                </form>
            </div>

        </div>

    </div>
</div>
<!-- end login -->
<script src="./js/jquery-3.2.1.min.js"></script>
<script>
    $(function () {
        $('.btns').click(function () {
            var user_name = $('#user_name').val();
            var user_pwd = $('#user_pwd').val();
            $.ajax({
                url:'LoginDO',
                type:'post',
                data:{user_pwd:user_pwd,user_name:user_name},
                dataType:'json',
                success:function (res) {
                    console.log(res);
                }
            });
        })
    })
</script>
@endsection

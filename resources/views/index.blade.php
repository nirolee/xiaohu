<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>小狐</title>

    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css"/>
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/6.0.0/normalize.min.css"/>--}}
    <link rel="stylesheet" href="css/base.css">

    <script src="/node_modules/jquery/dist/jquery.js"></script>
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>--}}
    <script src="/node_modules/angular/angular.js"></script>
    {{--<script src="//cdn.bootcss.com/angular.js/1.5.7/angular.min.js"></script>--}}
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    {{--<script src="//cdn.bootcss.com/angular-ui-router/1.0.0-rc.1/angular-ui-router.js"></script>--}}
    <script src="js/base.js"></script>
</head>
<body ng-app="xiaohu">
<div ng-controller="myController">
    <div class="navbar clearfix">
        <div class="fl">
            <div class="navbar-item brand">小狐</div>
            <div class="navbar-item">
                <input type="text">
            </div>
        </div>
        <div class="fr">
            <a ui-sref="home" class="navbar-item">首页</a>
            <a ui-sref="register" class="navbar-item">注册</a>
            <a ui-sref="login" class="navbar-item">登录</a>
        </div>
    </div>
    <div class="page">
        <div ui-view>

        </div>
    </div>
</div>
</body>
<script type="text/ng-template" id="home.tpl">
    <div class="container home">
        这是首页
    </div>
</script>
<script type="text/ng-template" id="register.tpl">
    <div class="container register" ng-controller="UserController">
        <div class="card">
        {{--<form ng-submit="User.signup()" name="userForm">--}}
            {{--<input type="text" ng-model="username">--}}
            {{--<button type="submit">register</button>--}}
        {{--</form>--}}
            <h3>注册</h3>
        <form ng-submit="User.signup()" name="userForm">
            <div class="input-group">
                <label>用户名</label>
                <input type="text" name="username"
                       ng-model="User.singup_data.username"
                       ng-model-options="{updateOn: 'blur'}"
                       ng-minlength="4"
                       ng-maxlength="10"
                       required >
                <div class="input-error-set" ng-if="userForm.username.$error.required && userForm.username.$touched">用户名必须填写</div>
                {{--<td ng-if="userForm.username.$error.required && userForm.username.$touched">用户名必须填写</td>--}}
                <div class="input-error-set" ng-if="userForm.username.$error.minlength || userForm.username.$error.maxlength">用户名必须为4-10个字符</div>
                <div class="input-error-set" ng-if="user.signup_username_exits">用户名已经存在！</div>
            </div>
                <div class="input-group">
                    <label>密码</label>
                    <input type="password" name="password"
                               ng-model="User.singup_data.password"
                               ng-model-options="{updateOn: 'blur'}"
                               ng-minlength="6"
                               ng-maxlength="20"
                               required>
                    <div class="input-error-set" ng-if="userForm.password.$error.required && userForm.password.$touched">密码必须填写</div>
                    <div class="input-error-set" ng-if="userForm.password.$error.minlength || userForm.password.$.error.maxlength">密码名必须为4-10个字符</div>
                </div>
            <button type="submit" ng-disabled="userForm.$invalid">注册</button>
        </form>
    </div>
    </div>
</script>
<script type="text/ng-template" id="login.tpl">
    <div class="container login">
        登录页
    </div>
</script>
</html>
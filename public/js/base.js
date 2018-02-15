var myApp=angular.module('xiaohu',['ui.router']);

myApp.config(['$stateProvider','$urlRouterProvider',function($stateProvider,$urlRouterProvider){
    $urlRouterProvider.otherwise('/home');
    $stateProvider.state('home',{
        url:'/home',
        templateUrl:'home.tpl'
    });
    $stateProvider.state('register',{
        url:'/register',
        templateUrl:'register.tpl'
    });
    $stateProvider.state('login',{
        url:'/login',
        templateUrl:'login.tpl'
    });
}]);

//用户验证服务
myApp.service('UserService',['$http','$state',function($http,$state){
    var me=this;
    me.signup=function(){
        $http.post();
        /*发送数据判断注册成功使用 $state.go('login') login是路由  跳转到登录页*/
    }
    me.username_exits=function(){
        $http.post('api/user/exits',{username:me.singup_data.username})
            .then(function(r){
                if(r.data.status && r.data.data.count){
                    me.signup_username_exits=true;
                }else{
                    me.signup_username_exits=false;
                }
            },function(e){
                console.log(e);
            });
    }
}]);

myApp.controller('UserController',['$scope','UserService',function($scope,UserService){
    $scope.user=UserService;
    $scope.$watch(function(){
        return UserService.singup_data;
    },function(newData,oldData){
        if(newData.username!=oldData.username){
            UserService.username_exits();
        }
    },true);
}]);

myApp.controller('myController',['$scope',function($scope){
    $scope.name='aa';
}]);
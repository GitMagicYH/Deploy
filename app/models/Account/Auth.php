<?php

namespace Account;

class Auth
{
    public static function filter()
    {
        $current = \Route::currentRouteAction();
        $currentController = \App::getController();
        return;
        //无需鉴权的route
        if (in_array($current, array(
            "Account\UserController@logoutAction",
            // "Account\UserController@qihooLoginAction",
        ))) {
            return;
        }

        if (!User::isLogin()) {
            User::qihooLogin();
            // return \Response::redirect('/account/user/qihoologin');
        }

        // HomeController下的action允许所有登录用户访问
        // if (!UserRole::hasRoute(User::getMail(), \Route::currentRouteAction())
        //     && stripos(\Route::currentRouteAction(), 'Account\HomeController') !== 0) {

        //     $title = "拒绝访问";
        //     $message = "你没有权限访问此页面，如果需要开通权限，请联系管理员";
        //     $view = \View::make('account.home.index', array(
        //         "title" => $title,
        //         "message" => $message,
        //     ));

        //     $view->setStatusCode(403);

        //     return $view;
        // }
    }
}

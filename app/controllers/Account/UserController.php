<?php

namespace Account;

class UserController
{
    /**
     * 退出登录
     */
    public function logoutAction()
    {
        User::logout();
        return \Response::redirect('/');
    }

    // public function qihooLoginAction()
    // {
    //     if (User::qihooLogin()) {
    //         return \Response::redirect('/');
    //     }
    // }
}

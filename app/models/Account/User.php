<?php

namespace Account;

class User
{
    const ACCOUNT_KEY    = "5a56706c8a6028f917542243";
    const ACCOUNT_IV     = "89067d6z";
    const ACCOUNT_EXPIRE = 604800; // 登录的最长有效期7天

    public static function getMail()
    {
        $user = self::getUser();
        return $user['mail'];
    }

    public static function getName()
    {
        $user = self::getUser();
        return $user['name'];
    }

    public static function isLogin()
    {
        $user = self::getUser();

        if (!empty($user["mail"]) && !empty($user["login"]) && (time() - $user["login"]) <= self::ACCOUNT_EXPIRE ) {
            return true;
        } else {
            return false;
        }
    }

    public static function getUser()
    {
        $user = array();
        if (!empty($_COOKIE["admin_user"])) {
            $admin = $_COOKIE["admin_user"];
            $u = json_decode(trim(mcrypt_decrypt(MCRYPT_3DES, self::ACCOUNT_KEY, base64_decode($admin), MCRYPT_MODE_CBC, self::ACCOUNT_IV)), true);
            if (!empty($u["mail"])) {
                $user = array(
                    "mail"  => $u["mail"],
                    "name"  => $u["name"],
                    "login" => $u["login"],
                    "ip"    => $u["ip"],
                );
            }
        }
        return $user;
    }

    public static function logout()
    {
        setcookie("admin_user", "", time() - 3600, "/");
    }

    /**
     * 第三方登录
     */
    public static function qihooLogin()
    {
        for ($i = 1; $i <= 3; $i ++) {
            $user = self::urlLogin();
            if (!empty($user["mail"])) {
                break;
            }
        }

        return self::login(array('mail' => $user['mail'], 'name' => $user['display']));
    }

    public static function login(array $user)
    {
        if (empty($user['mail']) || empty($user['name'])) {
            throw new \RuntimeException("login fail");
        } else {
            $u = array(
                'mail'  => $user['mail'],
                'name'  => $user['name'],
                'login' => time(),
                'ip'    => \Helper\Ip::getClientIp(),
            );
            $encrypt = base64_encode(mcrypt_encrypt(MCRYPT_3DES, self::ACCOUNT_KEY, json_encode($u), MCRYPT_MODE_CBC, self::ACCOUNT_IV));
            setcookie("admin_user", $encrypt, 0, "/");
            $_COOKIE["admin_user"] = $encrypt; // 由于此时cookie还未传给浏览器，$_COOKIE取不到已登录的信息，因此需要在这里未$_COOKIE补齐相关信息
        }

        return true;
    }

    /*
    #author=wizard 2011-7-22
    参数说明
    D_URL:跳转地址
    B_URL:备份的跳转地址
    Flag:访问者所使用的协议，http/https二选一。默认为http。
    返回结果：邮箱
     */
    public static function urlLogin($D_URL='https://login.ops.qihoo.net:4430/sec/login',$B_URL='https://tool4.ops.dxt.qihoo.net:4430/sec/login',$Flag='http')
    {
        #判断D_URL是否有效,无效换备份跳转地址B_URL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $D_URL);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    // allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    // return into a variable
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $contents = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($http_code!=200) {
            $D_URL = $B_URL;
        }
        //跳转
        $sid = \Input::get('sid');
        //获取sid
        if(empty($sid)) {
            self::jumpToLogin($D_URL, $Flag);
        } else {
            $url = $D_URL.'?sid='.$sid;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            $result = curl_exec($ch);
            curl_close($ch);

            $userInfo = array();
            if($result != 'None')
            {
                //解码
                $decoded  = json_decode($result,true);
                if (empty($decoded)) {
                    self::jumpToLogin($D_URL, $Flag);
                } else {
                    $userInfo = array(
                        'mail'    => $decoded['mail'],
                        'user'    => $decoded['user'],
                        'display' => $decoded['display'],
                    );
                }
            } else {
                self::jumpToLogin($D_URL, $Flag);
            }
            return $userInfo;
        }
    }

    public static function jumpToLogin($D_URL, $Flag)
    {
        $S_URL = $Flag.'://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        $d_url = $D_URL.'?ref='.$S_URL;
        header("Location:$d_url");exit;
    }
}

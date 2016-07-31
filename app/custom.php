<?php

use Caravel\Console\App;
use Caravel\Routing\ClassLoader;
use Caravel\Config\Config;

/*
|--------------------------------------------------------------------------
| Register The Class Loader
|--------------------------------------------------------------------------
|
| You may use the class loader to load your controllers and models.
| This is useful for keeping all of your classes in the "global" namespace.
|
*/

ClassLoader::addPaths(array(

    App::getAppRoot() . "/controllers",
    App::getAppRoot() . "/models",

))->register();

/*
|--------------------------------------------------------------------------
| Class Aliases
|--------------------------------------------------------------------------
|
| This array of class aliases will be registered when this application
| is started. However, feel free to register as many as you wish as
| the aliases are "lazy" loaded so they don't hinder performance.
|
*/

App::alias(Config::get("app")->aliases);

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application.
| By default we will build a basic log file setup which creates a single
| file for logs.
|
*/

Log::useFile("/tmp/caravel.log");

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors.
|
*/

App::error(function(Exception $e, App $app) {
	/**
     * 默认code被设置为100的异常是不重要的
     * 例如用户访问一个不存在的页面
     * 这种情况不需要记录日志
     */
    if ($e->getCode() != 100) {
        Log::exception($e);
    }

    // 未登录用户引导其登录，不显示任何额外信息
    if (!Account\User::isLogin()) {
        exit("<pre>" . $e . "</pre>");
    }

    /**
     * 区别对待不同类型的异常
     * 例如BadMethodException代表URL解析失败或页面不存在
     * RuntimeException代表请求合法但是程序出错无法给出预期结果
     * LogicException代表程序正常但是请求非法不予给出预期结果
     * 可以自定义其他类型Exception并区别处理
     */
    if ($e instanceof BadMethodCallException) {
        // 用户访问一个不存在的页面时进行提示并返回404状态码
        $title = "你所访问的页面不存在";
        $message = "检查地址是否正确或通过左侧菜单栏进行选择。<br />";
        $message .= $e->getMessage();

        $view = \View::make('account.home.index', array(
            "title" => $title,
            "message" => $message,
        ));

        $view->setStatusCode(404);
    } else {
        // 其他情况抛出的异常显示TRACE
        $title = "{$e->getMessage()}\n";
        $message = "";
        foreach ($e->getTrace() as $key => $trace) {
            $message .= "<p>";
            $message .= "[{$key}] ";
            $message .= "<span style='font-weight:bold;'>{$trace['function']}</span>";
            $message .= "<br />";
            $message .= $key === 0 ? $e->getFile() : (empty($trace['file']) ? "N/A" : $trace['file']);
            $message .= "<span style='font-weight:bold;'>:";
            $message .= $key === 0 ? $e->getLine() : (empty($trace['line']) ? "N/A" : $trace['line']);
            $message .= "</span>";
            $message .= "</p>";
        }

        $view = \View::make('account.home.index', array(
            "title" => $title,
            "message" => $message,
        ));
    }

    $app->render($view);
});

App::filter(function() {
	return Account\Auth::filter();
});

App::homepage("/Demo/index");

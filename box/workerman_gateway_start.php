<?php
/**
 * 消失的盒子应该入口
 * run with command
 * php start.php start
 */

ini_set('display_errors', 'on');
use Workerman\Worker;

// 定义BASE_PATH 基础路径常量
define('BASE_PATH',dirname(dirname(__DIR__)));

if(strpos(strtolower(PHP_OS), 'win') === 0)
{
    exit("start.php not support windows, please use start_for_win.bat\n");
}

// 检查扩展
if(!extension_loaded('pcntl'))
{
    exit("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}

if(!extension_loaded('posix'))
{
    exit("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}

// 标记是全局启动
define('GLOBAL_START', 1);

require_once BASE_PATH . '/vendor/autoload.php';

// 加载所有Applications/*/start.php，以便启动所有服务
foreach(glob(BASE_PATH.'/applications/*/start*.php') as $start_file)
{
    require_once $start_file;
}

// 加载辅助函数文件
require_once BASE_PATH . '/applications/box/service/CommonFunc.php';

// 加载盒子配置文件
require_once BASE_PATH . '/applications/box/config/Box.php';

// 运行所有服务
Worker::runAll();

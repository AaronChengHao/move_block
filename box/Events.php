<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id) {
        // 向当前client_id发送数据
        // Gateway::sendToClient($client_id, "Hello $client_id\n");
        // $responseMeg = json_encode(createFoo());
        // echo $responseMeg;
        // Gateway::sendToClient($client_id, $responseMeg);
        // 向所有人发送
        // Gateway::sendToAll("$client_id login\n");
    }
            //  定义数据格式   {"action":"login","param":{}}
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message) {
        echo $message . PHP_EOL;
        $responseMeg = $message;
        if (checkMessage( $message)) {
            $action = $message['action'];
            switch ($action) {
                case 'login':
                    // 请求登陆 分配uid
                    // $params = createNode();
                    // $params['uid'] = $client_id;
                    $params = ['uid' => $client_id];
                    break;
                case 'createFoo':
                    // 请求后台box坐标
                    $params = Box::createFoo();
                    break;
                case 'createNode':
                    // 请求后台node坐标
                    // $params = Box::createNode();
                    $params = createNode();
                    // $params['SN'] = $client_id;
                    break;
                case 'updateBox':
                    // 请求后台更新box坐标
                    $params = Box::updateBox();
                    break;
                case 'moveNode':
                    // 请求广播移动坐标
                    // $params = Box::moveNode();
                $params = $message['params'];
                    break;
                default:
                    $params = 'Fatal action!';
                    Gateway::sendToClient($client_id,$responseMeg);
                    break;
            }
            // 格式化返回信息
            $responseMeg = formatMessage($action,$params,$client_id);

            switch ($action) {
                case 'login':
                    Gateway::sendToClient($client_id,$responseMeg);
                    // $arr = Box::createFoo();
                    $jsonStr = json_encode(['action' => 'createFoo', 'params' => Box::createFoo()]);
                    Gateway::sendToClient($client_id,$jsonStr);
                // Gateway::sendToAll($responseMeg,null,[$client_id]);
                case 'createFoo':
                    Gateway::sendToClient($client_id,$responseMeg,[$client_id]);
                    break;
                case 'newlogin':
                    Gateway::sendToAll($responseMeg,null,[$client_id]);
                    break;
                case 'createNode':
                    Gateway::sendToAll($responseMeg);
                    break;
                case 'updateBox':
                    Gateway::sendToAll($responseMeg);
                    break;
                case 'moveNode':
                    Gateway::sendToAll($responseMeg,null,[$client_id]);
                    break;
                default:
                    Gateway::sendToClient($client_id,$responseMeg);
                    break;
            }

         } else{
            Gateway::sendToClient($client_id, 'Error Fromat :' . $responseMeg);


            // $responseMeg = json_encode(createFoo());
            // echo $responseMeg;
            // Gateway::sendToClient($client_id, $responseMeg);
            // $message = json_decode($message,true);
         }
   }

   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id) {
       // 向所有人发送
       $logoutMessage = [
            'action' => 'logout',
            'params' => [
                'uid' => $client_id
            ]
       ];
       GateWay::sendToAll(json_encode($logoutMessage));
   }
}

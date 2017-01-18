<?php
/**
 *消失的盒子 公共函数库文件
 *
 * @Author: Aaron
 * @Date:   2016-12-01 16:03:27
 * @Last Modified by:   Aaron
 * @Last Modified time: 2017-01-18 15:13:37
 */


function createFoo(){
    global $box_config;
    $top  = $box_config['width'] / 10;
    $left = $box_config['height'] / 10;
    $location_top  = rand(0,10) * $top;
    $location_left = rand(0,10) * $left;
    return ['top' => $location_top-20, 'left' => $location_left-20];
}

function createNode(){
    global $box_config;
    $top  = $box_config['width'] / 10;
    $left = $box_config['height'] / 10;
    $location_top  = rand(0,10) * $top;
    $location_left = rand(0,10) * $left;
    return ['top' => $location_top-20, 'left' => $location_left-20];
}

function checkMessage(&$message ){
    $message = json_decode($message,true);
    if (!is_array($message) || !isset($message['action'])) {
        return false;
    }else{
        return true;
    }
}

function formatMessage($action, $params, $userid = 'null'){
    $message = ['action' => $action, 'params' => $params,'id' => $userid];
    return json_encode($message);
}





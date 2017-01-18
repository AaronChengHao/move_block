<?php
/**
 *  box 盒子抽象
 *
 * @Author: Aaron
 * @Date:   2016-12-01 17:01:18
 * @Last Modified by:   Aaron
 * @Last Modified time: 2016-12-02 10:45:50
 */

class Box
{
    public static $is_box = false;

    public static $location = null;

    public function __construct()
    {

    }



    public static function createFoo()
    {
        $location = XRedis::get('box_location');
        if ($location === null) {
            $location = json_encode(createFoo());
            XRedis::set('box_location', $location, 1, 'h');
        }
        return json_decode($location,true);
    }

    public static function updateBox()
    {
        $update_location = createFoo();
        XRedis::set('box_location', json_encode($update_location), 1, 'h');
        return $update_location;
    }



    public static function createNode()
    {
        $location = createNode();
        return $location;
    }




}

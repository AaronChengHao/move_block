<?php
/**
 *  Predis
 *
 * @Author: Aaron
 * @Date:   2016-11-30 15:47:43
 * @Last Modified by:   Aaron
 * @Last Modified time: 2016-12-02 09:56:09
 */
class XRedis
{

    const CONFIG_FILE = '/config/redis.php';

    protected static $redis;

    public static function init()

    {

        self::$redis = new \Predis\Client(['host' => '120.27.117.52','port' => 6379]);

    }

    public static function set($key,$value,$time=null,$unit=null)
    {
      self::init();

      if ($time) {

        switch ($unit) {

          case 'h':

            $time *= 3600;

            break;

          case 'm':

          $time *= 60;

          break;

        case 's':

        case 'ms':

          break;

        default:

          throw new InvalidArgumentException('单位只能是 h m s ms');

          break;

      }

      if ($unit=='ms') {

        self::_psetex($key,$value,$time);

      } else {

        self::_setex($key,$value,$time);

      }

    } else {

      self::$redis->set($key,$value);

    }

  }

  public static function get($key)

  {

    self::init();

    return self::$redis->get($key);

  }

  public static function delete($key)

  {

    self::init();

    return self::$redis->del($key);

  }

  private static function _setex($key,$value,$time)

  {

    self::$redis->setex($key,$time,$value);

  }

  private static function _psetex($key,$value,$time)

  {

    self::$redis->psetex($key,$time,$value);

  }

}

<?php
/**
 * Created by PhpStorm.
 * User: slava
 * Date: 25.01.2018
 * Time: 15:47
 */
namespace Core;

class Logger
{
    /**
     * @param $data
     */
    public static function log($data){
        file_put_contents('debug.log', var_export($data, true));
    }
}
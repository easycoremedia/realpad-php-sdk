<?php

namespace Core;

class Logger
{
    /**
     * @param string
     * @description Log data
     */
    public static function log($data){
        file_put_contents('debug.log', var_export($data, true));
    }
}
<?php

class SpTranslate {

    private static $_tr = null;


    private static $_domain = "studypress";


    private static $_path ;


    private function __construct()
    {
        self::$_path = dirname( plugin_basename( __FILE__ ) ) . '/../languages/';
    }


    public static function getInstance()
    {
        if(is_null(self::$_tr)) {
            self::$_tr = new SpTranslate();
        }

        return self::$_tr;
    }

    public static function getDomain()
    {
        return self::$_domain;
    }

    public static function getPath()
    {
        return self::$_path;
    }

    public function __($str){
        return __($str,self::$_domain);
    }



    public function _e($str){
        _e($str,self::$_domain);
    }




} 
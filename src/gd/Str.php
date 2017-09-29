<?php
namespace xingrl\circle_task\gd;

class Str
{
    /**
     * 转换为驼峰式命名
     * @param $str
     * @return string
     */
    public static function convertToCamel( $str )
    {
        foreach( ['_', '-', ' '] as $v){
            while( ($pos = strpos($str , $v)) !== false ){
                $str = substr($str, 0, $pos) . ucfirst(substr($str, $pos+1));
            }
        }

        return lcfirst($str);
    }
}
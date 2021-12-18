<?php namespace Vankosoft\CmsBundle\Component;

use Spatie\Url\Url as SpatieUrl;

class Url
{

    public static function GetParameter( $param )
    {
        return $_GET[$param];
    }
    
    public static function ProjectsUrlGetId()
    {
        $url = SpatieUrl::fromString( $_SERVER['REQUEST_URI'] );
        
        return intval( $url->getSegment( 2 ) );
    }
}

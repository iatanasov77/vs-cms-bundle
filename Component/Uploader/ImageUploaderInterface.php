<?php namespace VS\CmsBundle\Component\Uploader;

use VS\CmsBundle\Model\ImageInterface;

interface ImageUploaderInterface
{
    public function upload( ImageInterface $image ): void;
    
    public function remove( string $path ): bool;
}

<?php namespace VS\CmsBundle\Component\Uploader;

use VS\CmsBundle\Model\FileInterface;

interface FileUploaderInterface
{
    public function upload( FileInterface $image ): void;
    
    public function remove( string $path ): bool;
}

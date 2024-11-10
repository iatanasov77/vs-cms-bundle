<?php namespace Vankosoft\CmsBundle\Component\Uploader;

use Vankosoft\CmsBundle\Model\Interfaces\FileInterface;

interface FileUploaderInterface
{
    public function upload( FileInterface $image ): void;
    
    public function remove( string $path ): bool;
}

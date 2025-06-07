<?php namespace Vankosoft\CmsBundle\Component\Uploader;

use League\Flysystem\Filesystem;
use Vankosoft\CmsBundle\Model\Interfaces\FileInterface;

interface FileUploaderInterface
{
    public function upload( FileInterface $image ): void;
    
    public function remove( string $path ): bool;
    
    public function getFilesystem(): Filesystem;
    
    public function fileSize( FileInterface $filemanagerFile );
}

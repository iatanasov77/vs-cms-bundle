<?php namespace Vankosoft\CmsBundle\Model;

use Vankosoft\CmsBundle\Model\Interfaces\FileManagerFileInterface;
use Vankosoft\CmsBundle\Model\Interfaces\FileManagerInterface;

class FileManagerFile extends File implements FileManagerFileInterface
{
    /** @var FileManagerInterface */
    protected $filemanager;
    
    public function getFilemanager(): FileManagerInterface
    {
        //return $this->filemanager;
        return $this->owner;
    }
    
    public function setFilemanager( FileManagerInterface $filemanager ): self
    {
        $this->filemanager  = $filemanager;
        $this->setOwner( $filemanager );
        
        return $this;
    }
}

<?php namespace Vankosoft\CmsBundle\Model;

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

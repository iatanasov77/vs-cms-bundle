<?php namespace VS\CmsBundle\Model;

class FileManagerFile extends File implements FileManagerFileInterface
{
    /** @var FileManagerInterface */
    protected $filemanager;
    
    public function getFilemanager(): string
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

<?php namespace VS\CmsBundle\Model;

class FileManagerFile extends File implements FileManagerFileInterface
{
    /** @var FileManagerInterface */
    protected $filemanager;
    
    public function getFilemanager(): string
    {
        return $this->filemanager;
    }
    
    public function setFilemanager( FileManagerInterface $filemanager ): self
    {
        $this->filemanager  = $filemanager;
        
        return $this;
    }
}

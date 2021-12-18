<?php namespace Vankosoft\CmsBundle\Model;

class FileManagerFile extends File implements FileManagerFileInterface
{
    /** @var FileManagerInterface */
    protected $filemanager;
    
    /** @var string */
    protected $originalName;
    
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
    
    public function getOriginalName(): string
    {        
         return $this->originalName;
    }
    
    public function setOriginalName( string $originalName ): self
    {
        $this->originalName = $originalName;
        
        return $this;
    }
}

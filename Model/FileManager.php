<?php namespace VS\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class FileManager implements FileManagerInterface
{
    /** @var integer */
    protected $id;
    
    /** @var string */
    protected $code;
    
    /** @var string */
    protected $title;
    
    /** @var PageInterface */
    protected $files;
    
    public function __construct()
    {
        $this->files = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getCode()
    {
        return $this->code;
    }
    
    public function setCode( $code )
    {
        $this->code = $code;
        
        return $this;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function setTitle( $title )
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * @return Collection|FileManagerFile[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }
    
    public function addFile( FileManagerFile $file ) : FileManagerInterface
    {
        if ( ! $this->files->contains( $file ) ) {
            $this->files[] = $file;
            $file->setFilemanager( $this );
        }
        
        return $this;
    }
    
    public function removeFile( FileManagerFile $file ) : FileManagerInterface
    {
        if ( ! $this->files->contains( $file ) ) {
            $this->files->removeElement( $file );
        }
        
        return $this;
    }
}

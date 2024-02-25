<?php namespace Vankosoft\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vankosoft\ApplicationBundle\Model\Traits\TaxonDescendentTrait;
use Vankosoft\CmsBundle\Model\Interfaces\FileManagerInterface;
use Vankosoft\CmsBundle\Model\Interfaces\FileManagerFileInterface;
use Vankosoft\CmsBundle\Model\Interfaces\PageInterface;

class FileManager implements FileManagerInterface
{
    use TaxonDescendentTrait;
    
    /** @var integer */
    protected $id;
    
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
    
    /**
     * @return Collection|FileManagerFile[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }
    
    public function addFile( FileManagerFileInterface $file ): self
    {
        if ( ! $this->files->contains( $file ) ) {
            $this->files[] = $file;
            $file->setFilemanager( $this );
        }
        
        return $this;
    }
    
    public function removeFile( FileManagerFileInterface $file ): self
    {
        if ( ! $this->files->contains( $file ) ) {
            $this->files->removeElement( $file );
        }
        
        return $this;
    }
    
    public function __toString()
    {
        return $this->taxon ? $this->taxon->getName() : '';
    }
}

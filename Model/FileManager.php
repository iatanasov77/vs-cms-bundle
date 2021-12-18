<?php namespace Vankosoft\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Vankosoft\ApplicationBundle\Model\Interfaces\TaxonInterface;
use Vankosoft\ApplicationBundle\Model\Taxon;

class FileManager implements FileManagerInterface
{
    /** @var integer */
    protected $id;
    
    /** @var TaxonInterface */
    protected $taxon;
    
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
        return $this->taxon ? $this->taxon->getCode() : '';
    }
    
    public function setCode( $code )
    {
        if ( ! $this->taxon ) {
            // Create new taxon into the controller and set the properties passed from form
            return $this;
        }
        $this->taxon->setCode( $code );
        
        return $this;
    }
    
    public function getTitle(): string
    {
        return $this->taxon ? $this->taxon->getName() : '';
    }
    
    public function setTitle( $title )
    {
        if ( ! $this->taxon ) {
            // Create new taxon into the controller and set the properties passed from form
            return $this;
        }
        $this->taxon->setName( $title );
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getTaxon(): ?TaxonInterface
    {
        return $this->taxon;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setTaxon( ?TaxonInterface $taxon ): void
    {
        $this->taxon = $taxon;
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
    
    public function __toString()
    {
        return $this->taxon ? $this->taxon->getName() : '';
    }
}

<?php namespace Vankosoft\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Vankosoft\ApplicationBundle\Model\Interfaces\TaxonInterface;
use Vankosoft\ApplicationBundle\Model\Taxon;

/**
 * Page Category Model
 */
class DocumentCategory implements DocumentCategoryInterface
{
    /** @var mixed */
    protected $id;
    
    /** @var Collection|Document[] */
    protected $documents;
    
    /** @var TaxonInterface */
    protected $taxon;
    
    public function __construct()
    {
        $this->documents    = new ArrayCollection();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getDocuments(): Collection
    {
        return $this->documents;
    }
    
    public function addDocument( DocumentInterface $document ): DocumentCategoryInterface
    {
        if ( ! $this->documents->contains( $document ) ) {
            $this->documents[] = $document;
            $document->setCategory( $this );
        }
        
        return $this;
    }
    
    public function removeDocument( DocumentInterface $document ): DocumentCategoryInterface
    {
        if ( $this->documents->contains( $document ) ) {
            $this->documents->removeElement( $document );
            $document->setCategory( null );
        }
        
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
    
    public function getName()
    {
        return $this->taxon ? $this->taxon->getName() : '';
    }
    
    public function setName( string $name ) : self
    {
        if ( ! $this->taxon ) {
            // Create new taxon into the controller and set the properties passed from form
            return $this;
        }
        $this->taxon->setName( $name );
        
        return $this;
    }
    
    public function __toString()
    {
        return $this->taxon ? $this->taxon->getName() : '';
    }
}

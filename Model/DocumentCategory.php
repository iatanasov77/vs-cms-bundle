<?php namespace Vankosoft\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vankosoft\ApplicationBundle\Model\Traits\TaxonDescendentTrait;
use Vankosoft\CmsBundle\Model\Interfaces\DocumentCategoryInterface;
use Vankosoft\CmsBundle\Model\Interfaces\DocumentInterface;

/**
 * Page Category Model
 */
class DocumentCategory implements DocumentCategoryInterface
{
    use TaxonDescendentTrait;
    
    /** @var mixed */
    protected $id;
    
    /** @var Collection|Document[] */
    protected $documents;
    
    /** @var DocumentCategoryInterface */
    protected $parent;
    
    /** @var Collection|DocumentCategory[] */
    protected $children;
    
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
    
    public function addDocument( DocumentInterface $document ): self
    {
        if ( ! $this->documents->contains( $document ) ) {
            $this->documents[] = $document;
            $document->setCategory( $this );
        }
        
        return $this;
    }
    
    public function removeDocument( DocumentInterface $document ): self
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
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setParent(?DocumentInterface $parent): DocumentInterface
    {
        $this->parent = $parent;
        
        return $this;
    }
    
    public function getChildren(): Collection
    {
        return $this->children;
    }
    
    public function __toString()
    {
        return $this->taxon ? $this->taxon->getName() : '';
    }
}

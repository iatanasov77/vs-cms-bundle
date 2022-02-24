<?php namespace Vankosoft\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TocPage implements TocPageInterface
{
    /** @var integer */
    protected $id;
    
    /** @var TaxonInterface */
    protected $taxon;
    
    /** @var PageCategoryInterface */
    protected $parent;
    
    /** @var Collection|PageCategory[] */
    protected $children;
    
    /** @var DocumentInterface */
    protected $document;
    
    /** @var string */
    protected $text;
    
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
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
     * {@inheritdoc}
     */
    public function getParent(): ?PageCategoryInterface
    {
        return $this->parent;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setParent(?PageCategoryInterface $parent) : PageCategoryInterface
    {
        $this->parent = $parent;
        
        return $this;
    }
    
    public function getChildren(): Collection
    {
        return $this->children;
    }
    
    public function getDocument(): ?DocumentInterface
    {
        return $this->document;
    }
    
    public function getText(): ?string
    {
        return $this->text;
    }
    
    public function setText( ?string $text ): self
    {
        $this->text = $text;
        
        return $this;
    }
    
    
    /*
     * Proxy Methods
     */
    
    public function getTitle(): string
    {
        return $this->taxon ? $this->taxon->getName() : '';
    }
    
    public function setTitle( string $title ): self
    {
        if ( ! $this->taxon ) {
            // Create new taxon into the controller and set the properties passed from form
            return $this;
        }
        $this->taxon->setName( $title );
        
        return $this;
    }
    
    public function getName(): string
    {
        return $this->taxon ? $this->taxon->getName() : '';
    }
    
    public function setName( string $name ): self
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

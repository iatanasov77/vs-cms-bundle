<?php namespace Vankosoft\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vankosoft\ApplicationBundle\Model\Interfaces\TaxonInterface;
use Vankosoft\ApplicationBundle\Model\Interfaces\LoggableObjectInterface;

class TocPage implements TocPageInterface, LoggableObjectInterface
{
    /** @var integer */
    protected $id;
    
    /** @var TaxonInterface */
    protected $taxon;
    
    /**
     * @var TocPageInterface|null
     */
    protected $root;
    
    /** @var TocPageInterface */
    protected $parent;
    
    /** @var Collection|TocPageInterface[] */
    protected $children;
    
    /** @var DocumentInterface */
    protected $document;
    
    /** @var string */
    protected $title;
    
    /** @var string */
    protected $description;
    
    /** @var string */
    protected $text;
    
    /** @var string */
    protected $locale;
    
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
    
    public function getRoot(): ?TocPageInterface
    {
        return $this->root;
    }
    
    public function setRoot( ?TocPageInterface $root ): self
    {
        $this->root = $root;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getParent(): ?TocPageInterface
    {
        return $this->parent;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setParent(?TocPageInterface $parent): self
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
    
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    public function setTitle( $title ): self
    {
        $this->title = $title;
        
        return $this;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    public function setDescription( $description ): self
    {
        $this->description = $description;
        
        return $this;
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
    
    /**
     * {@inheritDoc}
     * @see \Vankosoft\ApplicationBundle\Model\Interfaces\LoggableObjectInterface::getTranslatableLocale()
     */
    public function getTranslatableLocale(): ?string
    {
        return $this->locale;
    }
    
    public function setTranslatableLocale($locale): self
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    public function __toString()
    {
        return $this->title;
    }
}

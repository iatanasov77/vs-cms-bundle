<?php namespace VS\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use VS\ApplicationBundle\Model\Interfaces\TaxonInterface;
use VS\ApplicationBundle\Model\Taxon;

/**
 * Page Category Model
 */
class PageCategory implements PageCategoryInterface
{
    /** @var mixed */
    protected $id;
    
    /** @var PageCategoryInterface */
    protected $parent;
    
    /** @var Collection|PageCategory[] */
    protected $children;
    
    /** @var Collection|Page[] */
    protected $pages;
    
    /** @var TaxonInterface */
    protected $taxon;
    
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->pages    = new ArrayCollection();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
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
    public function setParent(?PageCategoryInterface $parent) : self
    {
        $this->parent = $parent;
        
        return $this;
    }
    
    public function getChildren() : Collection
    {
        return $this->children;
    }
    
    /**
     * @return Collection|Page[]
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }
    
    public function addPage( Page $page ) : self
    {
        if ( ! $this->pages->contains( $page ) ) {
            $this->pages[] = $page;
            $page->addCategory( $this );
        }
        
        return $this;
    }
    
    public function removePage( Page $page ) : self
    {
        if ( ! $this->pages->contains( $page ) ) {
            $this->pages->removeElement( $page );
            $page->removeCategory( $this );
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
    public function setTaxon(?TaxonInterface $taxon): void
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

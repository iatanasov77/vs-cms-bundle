<?php namespace Vankosoft\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vankosoft\ApplicationBundle\Model\Traits\TaxonDescendentTrait;
use Vankosoft\CmsBundle\Model\Interfaces\PageCategoryInterface;
use Vankosoft\CmsBundle\Model\Interfaces\PageInterface;

/**
 * Page Category Model
 */
class PageCategory implements PageCategoryInterface
{
    use TaxonDescendentTrait;
    
    /** @var integer */
    protected $id;
    
    /** @var PageCategoryInterface */
    protected $parent;
    
    /** @var Collection|PageCategory[] */
    protected $children;
    
    /** @var Collection|Page[] */
    protected $pages;
    
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
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setParent(?PageCategoryInterface $parent): PageCategoryInterface
    {
        $this->parent = $parent;
        
        return $this;
    }
    
    public function getChildren(): Collection
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
    
    public function addPage( PageInterface $page ): self
    {
        if ( ! $this->pages->contains( $page ) ) {
            $this->pages[] = $page;
            $page->addCategory( $this );
        }
        
        return $this;
    }
    
    public function removePage( PageInterface $page ): self
    {
        if ( $this->pages->contains( $page ) ) {
            $this->pages->removeElement( $page );
            $page->removeCategory( $this );
        }
        
        return $this;
    }
    
    public function getNameTranslated( string $locale )
    {
        return $this->taxon ? $this->taxon->getTranslation( $locale )->getName() : '';
    }
    
    public function __toString()
    {
        return $this->taxon ? $this->taxon->getName() : '';
    }
}

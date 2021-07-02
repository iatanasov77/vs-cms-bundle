<?php namespace VS\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 *  Can to be a Taxonomy but for now i think it's better to be a separate type
 *  ===========================================================================
 *  
 *  -------------------------
 *  Here is used this Manual:
 *  -------------------------
 *  https://github.com/doctrine-extensions/DoctrineExtensions/blob/v2.4.x/doc/tree.md
 */
class TocPage implements TocPageInterface
{
    /** @var integer */
    protected $id;
    
    /** @var integer */
    protected $title;
    
    /** @var PageInterface */
    protected $page;
    
    /** @var integer */
    protected $lft;
    
    /** @var integer */
    protected $lvl;
    
    /** @var integer */
    protected $rgt;
    
    /** @var TocPageInterface */
    protected $root;
    
    /** @var TocPageInterface */
    protected $parent;
    
    /** @var Collection */
    protected $children;
    
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
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
    
    public function getPage(): ?PageInterface
    {
        return $this->page;
    }
    
    public function setPage( $page )
    {
        $this->page = $page;
        
        return $this;
    }
    
    
    public function getRoot()
    {
        return $this->root;
    }
    
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function getChildren(): Collection
    {
        return $this->children;
    }
}

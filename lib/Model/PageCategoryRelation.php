<?php namespace VS\CmsBundle\Model;

class PageCategoryRelation implements PageCategoryRelationInterface
{
    /** @var integer */
    protected $id;
    
    protected $page;
    
    protected $category;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getPage() : PageInterface
    {
        return $this->page;
    }
    
    public function getCategory() : PageCategoryInterface
    {
        return $this->category;
    }
}

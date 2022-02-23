<?php namespace Vankosoft\CmsBundle\Model;

class Document implements DocumentInterface
{
    /** @var integer */
    protected $id;
    
    /** @var string */
    protected $title;
    
    /** @var TocPageInterface */
    protected $tocRootPage;
    
    /** @var string */
    protected $locale;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    public function setTitle( $title )
    {
        $this->title = $title;
        
        return $this;
    }
    
    public function getTocRootPage(): ?TocPageInterface
    {
        return $this->tocRootPage;
    }
    
    public function setTocRootPage( TocPageInterface $tocRootPage )
    {
        $this->tocRootPage  = $tocRootPage;
        
        return $this;
    }
    
    public function getLocale()
    {
        return $this->locale;
    }
    
    public function setTranslatableLocale( $locale ): self
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    public function getPage(): ?PageInterface
    {
        return ! empty( $this->tocRootPage->getChildren() ) ? $this->tocRootPage->getChildren()[0]->getPage() : $this->tocRootPage->getPage();
    }
}

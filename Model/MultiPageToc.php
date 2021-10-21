<?php namespace VS\CmsBundle\Model;

class MultiPageToc implements MultiPageTocInterface
{
    /** @var integer */
    protected $id;
    
    /** @var string */
    protected $tocTitle;
    
    /** @var PageInterface */
    protected $mainPage;
    
    /** @var TocPageInterface */
    protected $tocRootPage;
    
    /** @var string */
    protected $locale;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTocTitle(): string
    {
        return $this->tocTitle;
    }
    
    public function setTocTitle( $tocTitle )
    {
        $this->tocTitle = $tocTitle;
        
        return $this;
    }
    
    public function getMainPage(): ?PageInterface
    {
        return $this->mainPage;
    }
    
    public function setMainPage( ?PageInterface $mainPage )
    {
        $this->mainPage  = $mainPage;
        
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
    
    public function setTranslatableLocale( $locale )
    {
        $this->locale = $locale;
        
        return $this;
    }
}

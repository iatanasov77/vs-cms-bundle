<?php namespace VS\CmsBundle\Model;

class MultiPageToc implements MultiPageTocInterface
{
    /** @var integer */
    protected $id;
    
    /** @var string */
    protected $tocTitle;
    
    /** @var TocPageInterface */
    protected $tocRootPage;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTocTitle(): string
    {
        return $this->tocTitle;
    }
    
    public function setTocTitle( $tocTitle ): string
    {
        $this->tocTitle = $tocTitle;
        
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
}

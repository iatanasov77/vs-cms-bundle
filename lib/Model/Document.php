<?php namespace VS\CmsBundle\Model;

class Document implements DocumentInterface
{
    /** @var integer */
    protected $id;
    
    /** @var string */
    protected $title;
    
    /** @var MultipageToc */
    protected $multipageToc;
    
    /** @var string */
    protected $locale;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle() : ?string
    {
        return $this->title;
    }
    
    public function setTitle( $title )
    {
        $this->title = $title;
        return $this;
    }
    
    public function getMultipageToc()
    {
        return $this->multipageToc;
    }
    
    public function setMultipageToc( $multipageToc )
    {
        $this->multipageToc = $multipageToc;
        
        return $this;
    }
    
    public function setTranslatableLocale( $locale )
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    public function getPage()
    {
        return $this->multipageToc && ! empty( $this->multipageToc->getTocRootPage()->getChildren() ) ? 
                    $this->multipageToc->getTocRootPage()->getChildren()[0]->getPage() : 
                    $this->multipageToc->getPage();
    }
}

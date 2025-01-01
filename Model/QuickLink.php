<?php namespace Vankosoft\CmsBundle\Model;

use Sylius\Component\Resource\Model\ToggleableTrait;
use Vankosoft\CmsBundle\Model\Interfaces\QuickLinkInterface;
use Vankosoft\ApplicationBundle\Model\Traits\TranslatableTrait;

class QuickLink implements QuickLinkInterface
{
    use ToggleableTrait;
    use TranslatableTrait;
    
    /** @var integer */
    protected $id;
    
    /** @var string */
    protected $linkText;
    
    /** @var string */
    protected $linkPath;
    
    public function __construct()
    {
        $this->fallbackLocale   = 'en_US';
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getLinkText(): ?string
    {
        return $this->linkText;
    }
    
    public function setLinkText($linkText)
    {
        $this->linkText   = $linkText;
        
        return $this;
    }
    
    public function getLinkPath(): ?string
    {
        return $this->linkPath;
    }
    
    public function setLinkPath($linkPath)
    {
        $this->linkPath  = $linkPath;
        
        return $this;
    }
    
    public function isPublished(): ?bool
    {
        return $this->enabled;
    }
}
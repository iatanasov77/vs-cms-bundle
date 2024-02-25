<?php namespace Vankosoft\CmsBundle\Model;

use Vankosoft\CmsBundle\Model\Interfaces\SliderItemInterface;
use Vankosoft\CmsBundle\Model\Interfaces\SliderItemPhotoInterface;
use Vankosoft\ApplicationBundle\Model\Traits\TranslatableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

class SliderItem implements SliderItemInterface
{
    use ToggleableTrait;    // About enabled field - $enabled (public)
    use TranslatableTrait;
    
    /** @var integer */
    protected $id;
    
    /** @var string */
    protected $title;
    
    /** @var string */
    protected $description;
    
    /** @var SliderItemPhotoInterface */
    protected $photo;
    
    /** @var bool */
    protected $enabled = true;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    public function setTitle($title): self
    {
        $this->title   = $title;
        
        return $this;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    public function setDescription($description): self
    {
        $this->description  = $description;
        
        return $this;
    }
    
    public function getPhoto(): SliderItemPhotoInterface
    {
        return $this->photo;
    }
    
    public function setPhoto($photo): self
    {
        $this->photo = $photo;
        
        return $this;
    }
    
    public function isPublic(): bool
    {
        return $this->enabled;
    }
    
    public function isPublished(): bool
    {
        return $this->enabled;
    }
}
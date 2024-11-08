<?php namespace Vankosoft\CmsBundle\Model;

use Vankosoft\CmsBundle\Model\Interfaces\SliderItemInterface;
use Vankosoft\CmsBundle\Model\Interfaces\SliderItemPhotoInterface;
use Vankosoft\CmsBundle\Model\Interfaces\SliderInterface;
use Vankosoft\ApplicationBundle\Model\Traits\TranslatableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TimestampableTrait;

class SliderItem implements SliderItemInterface
{
    use ToggleableTrait;    // About enabled field - $enabled (public)
    use TimestampableTrait;
    use TranslatableTrait;
    
    /** @var integer */
    protected $id;
    
    /** @var string */
    protected $title;
    
    /** @var string */
    protected $description;
    
    /** @var string */
    protected $url;
    
    /** @var SliderItemPhotoInterface */
    protected $photo;
    
    /** @var bool */
    protected $enabled = true;
    
    /** @var SliderInterface */
    protected $slider;
    
    /** @var integer */
    protected $position;
    
    public function __construct()
    {
        $this->fallbackLocale   = 'en_US';
    }
    
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
    
    public function getUrl(): ?string
    {
        return $this->url;
    }
    
    public function setUrl( ?string $url ): self
    {
        $this->url = $url;
        
        return $this;
    }
    
    public function getPhoto(): ?SliderItemPhotoInterface
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
    
    public function getSlider(): ?SliderInterface
    {
        return $this->slider;
    }
    
    public function setSlider( SliderInterface $slider ): self
    {
        $this->slider   = $slider;
        
        return $this;
    }
    
    public function getPosition()
    {
        return $this->position;
    }
    
    public function setPosition( $position ): self
    {
        $this->position = $position;
        
        return $this;
    }
}
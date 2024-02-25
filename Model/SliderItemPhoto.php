<?php namespace Vankosoft\CmsBundle\Model;

use Vankosoft\CmsBundle\Model\Interfaces\SliderItemPhotoInterface;
use Vankosoft\CmsBundle\Model\Interfaces\SliderItemInterface;

class SliderItemPhoto extends File implements SliderItemPhotoInterface
{
    /**
     * @ORM\OneToOne(targetEntity=SliderItem::class, inversedBy="photo", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $owner;
    
    public function getSliderItem(): SliderItemInterface
    {
        return $this->owner;
    }
    
    public function setSlider( Slider $slider ): self
    {
        $this->setOwner( $slider );
        
        return $this;
    }
}
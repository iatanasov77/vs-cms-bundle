<?php namespace Vankosoft\CmsBundle\Model;

use Vankosoft\CmsBundle\Model\Interfaces\SliderItemPhotoInterface;
use Vankosoft\CmsBundle\Model\Interfaces\SliderItemInterface;

class SliderItemPhoto extends File implements SliderItemPhotoInterface
{
    /** @var SliderItemInterface */
    protected $owner;
    
    public function getSliderItem(): SliderItemInterface
    {
        return $this->owner;
    }
    
    public function setSliderItem( SliderItemInterface $sliderItem ): self
    {
        $this->setOwner( $sliderItem );
        
        return $this;
    }
}
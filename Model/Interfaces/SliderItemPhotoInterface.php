<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

interface SliderItemPhotoInterface extends FileInterface
{
    public function getSliderItem(): SliderItemInterface;
}

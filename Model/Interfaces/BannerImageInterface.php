<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

interface BannerImageInterface extends FileInterface
{
    public function getBanner(): BannerInterface;
}

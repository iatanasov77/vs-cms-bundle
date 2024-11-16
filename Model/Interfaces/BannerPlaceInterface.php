<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Sylius\Component\Resource\Model\ResourceInterface;
use Vankosoft\ApplicationBundle\Model\Interfaces\TaxonDescendentInterface;
use Doctrine\Common\Collections\Collection;

interface BannerPlaceInterface extends ResourceInterface, TaxonDescendentInterface
{
    public function getImagineFilter(): string;
    public function getPublishedBanners(): Collection;
    public function getBanners(): Collection;
    public function addBanner( BannerInterface $banner ): self;
    public function removeBanner( BannerInterface $banner ): self;
}

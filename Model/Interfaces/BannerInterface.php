<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Vankosoft\ApplicationBundle\Model\Interfaces\TranslatableInterface;

interface BannerInterface extends ResourceInterface, TranslatableInterface
{
    public function getTitle(): ?string;
    public function getUrl(): ?string;
    public function getImage(): ?BannerImageInterface;
    public function isPublished(): bool;
    public function getPlaces(): Collection;
}

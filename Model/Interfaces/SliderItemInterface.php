<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Sylius\Component\Resource\Model\ResourceInterface;
use Vankosoft\ApplicationBundle\Model\Interfaces\TranslatableInterface;

interface SliderItemInterface extends
    ResourceInterface,
    TranslatableInterface
{
    public function getTitle(): ?string;
    public function getDescription(): ?string;
    public function getPhoto(): ?SliderItemPhotoInterface;
    public function isPublished(): bool;
}

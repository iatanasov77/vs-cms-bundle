<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Vankosoft\ApplicationBundle\Model\Interfaces\TranslatableInterface;
use Doctrine\Common\Collections\Collection;

interface TocPageInterface extends ResourceInterface, SlugAwareInterface, TranslatableInterface
{
    public function getTitle(): ?string;
    public function getDescription(): ?string;
    public function getText(): ?string;
    public function getChildren(): Collection;
    
    public function getSlug(): ?string;
}

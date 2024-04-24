<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Vankosoft\ApplicationBundle\Model\Interfaces\TranslatableInterface;
use Doctrine\Common\Collections\Collection;

interface PageInterface extends
    ResourceInterface,
    SlugAwareInterface,
    TimestampableInterface,
    ToggleableInterface,
    TranslatableInterface
{
    public function getCategories(): Collection;
    
    public function addCategory( PageCategoryInterface $category ): self;
    
    public function removeCategory( PageCategoryInterface $category ): self;
    
    public function getSlug(): ?string;
    
    public function getTitle(): ?string;
    
    public function getText(): ?string;
    
    /*
    public function getMetaKeywords(): ?string;
    
    public function setMetaKeywords(?string $metaKeywords): void;
    
    public function getMetaDescription(): ?string;
    
    public function setMetaDescription(?string $metaDescription): void;
    */
    
    public function getPublished(): ?bool;
}

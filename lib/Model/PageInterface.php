<?php namespace VS\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface PageInterface extends
    ResourceInterface,
    SlugAwareInterface,
    TimestampableInterface,
    ToggleableInterface,
    TranslatableInterface
{
    public function getName(): ?string;
    
    public function setName(?string $name): void;
    
    public function getDescription(): ?string;
    
    public function setDescription(?string $description): void;
    
    public function getMetaKeywords(): ?string;
    
    public function setMetaKeywords(?string $metaKeywords): void;
    
    public function getMetaDescription(): ?string;
    
    public function setMetaDescription(?string $metaDescription): void;
    
    /**
     * @return ProductTranslationInterface
     */
    public function getTranslation(?string $locale = null): TranslationInterface;
}

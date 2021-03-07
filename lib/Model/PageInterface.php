<?php namespace VS\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslationInterface;
use Doctrine\Common\Collections\Collection;

interface PageInterface extends
    ResourceInterface,
    SlugAwareInterface,
    TimestampableInterface,
    ToggleableInterface,
    TranslatableInterface
{
    public function setTranslatableLocale( $locale ) : self;
    
    public function getCategories();
    
    //public function addCategory( PageCategory $category ) : self;
    
    //public function removeCategory( PageCategory $category ) : self;
    
    public function getSlug() : ?string;
    
    public function getTitle() : ?string;
    
    public function getText() : ?string;
    
    /*
    public function getMetaKeywords(): ?string;
    
    public function setMetaKeywords(?string $metaKeywords): void;
    
    public function getMetaDescription(): ?string;
    
    public function setMetaDescription(?string $metaDescription): void;
    */
    
    public function getPublished() : ?bool;
}

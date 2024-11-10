<?php namespace Vankosoft\CmsBundle\Model;

use Sylius\Component\Resource\Model\TranslationInterface;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Vankosoft\ApplicationBundle\Model\Traits\TranslatableTrait;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Vankosoft\ApplicationBundle\Model\Interfaces\TaxonLeafInterface;
use Vankosoft\ApplicationBundle\Model\Traits\TaxonLeafTrait;
use Vankosoft\ApplicationBundle\Model\Interfaces\LoggableObjectInterface;
use Vankosoft\CmsBundle\Model\Interfaces\PageInterface;
use Vankosoft\CmsBundle\Model\Interfaces\PageCategoryInterface;

class Page implements PageInterface, TaxonLeafInterface, LoggableObjectInterface
{
    use TimestampableTrait;
    use ToggleableTrait;    // About enabled field - $enabled (published)
    use TranslatableTrait;
    use TaxonLeafTrait;
    
    const TYPE_SINGLE_PAGE  = 1;
    const TYPE_MULTI_PAGE   = 2;
    
    /** @var integer */
    protected $id;

    /** @var string */
    protected $slug;

    /** @var string */
    protected $title;
    
    /** @var string */
    protected $description;
    
    /** @var string */
    protected $tags   = '';

    /** @var string */
    protected $text;
    
    /** @var Collection|PageCategory[] */
    protected $categories;
    
    public function __construct()
    {
        $this->fallbackLocale   = 'en_US';
        $this->categories = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return Collection|PageCategory[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }
    
    public function setCategories( Collection $categories ): self
    {
        $this->categories   = $categories;
        
        return $this;
    }
    
    public function addCategory( PageCategoryInterface $category ): self
    {
        if ( ! $this->categories->contains( $category ) ) {
            $this->categories[] = $category;
        }
        
        return $this;
    }
    
    public function removeCategory( PageCategoryInterface $category ): self
    {
        if ( $this->categories->contains( $category ) ) {
            $this->categories->removeElement( $category );
        }
        
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setSlug($slug=null): void
    {
        $this->slug = $slug;
        //return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    public function getTags(): ?string
    {
        return $this->tags;
    }
    
    public function setTags($tags)
    {
        $this->tags = $tags;
        
        return $this;
    }
    
    public function getPublished(): ?bool
    {
        return $this->enabled;
    }
    
    public function setPublished( ?bool $published ): PageInterface
    {
        $this->enabled = (bool) $published;
        return $this;
    }
    
    /*
     * @NOTE: From TaxonLeafInterface
     */
    public function getName(): ?string
    {
        return $this->title;
    }
    
    public function isPublished()
    {
        return $this->isEnabled();
    }
}

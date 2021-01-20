<?php namespace VS\CmsBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\SlugAwareInterface;

use VS\ApplicationBundle\Model\Interfaces\TaxonInterface;
use VS\ApplicationBundle\Model\Interfaces\PageInterface;

/**
 * Page Model
 * 
 * @ORM\MappedSuperclass
 */
class Page implements PageInterface, SlugAwareInterface
{
    use ToggleableTrait;    // About enabled field - $enabled (published)
    
    /** @var integer */
    protected $id;

    /** @var string */
    protected $slug;

    /** @var string */
    protected $title;

    /** @var string */
    protected $text;
    
    /** @var PageCategoryInterface */
    protected $category;
    
    /** @var string */
    protected $locale;
    
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getSlug() : ?string
    {
        return $this->slug;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setSlug($slug=null) : void
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

    public function getCategory(): ?PageCategoryInterface
    {
        return $this->category;
    }
    
    public function setCategory(?PageCategoryInterface $category): self
    {
        $this->category = $category;
        
        return $this;
    }
}

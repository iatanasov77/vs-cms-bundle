<?php namespace VS\CmsBundle\Model;

use Sylius\Component\Resource\Model\TranslationInterface;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use VS\ApplicationBundle\Model\Interfaces\TaxonLeafInterface;
use VS\ApplicationBundle\Model\Traits\TaxonLeafTrait;

class Page implements PageInterface, TaxonLeafInterface
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
    protected $text;
    
    /** @var Collection|PageCategory[] */
    protected $categories;
    
    /** @var string */
    protected $locale;
    
    /** @var integer */
    protected $type;
    
    protected $multipageToc;
    
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return Collection|PageCategory[]
     */
    public function getCategories()
    {
        return $this->categories;
    }
    
    public function addCategory( PageCategory $category ) : PageInterface
    {
        if ( ! $this->categories->contains( $category ) ) {
            $this->categories[] = $category;
        }
        
        return $this;
    }
    
    public function removeCategory( PageCategory $category ) : PageInterface
    {
        if ( $this->categories->contains( $category ) ) {
            $this->categories->removeElement( $category );
        }
        
        return $this;
    }
    
    public function getTranslatableLocale() : ?string
    {
        return $this->locale;
    }
    
    public function setTranslatableLocale($locale) : PageInterface
    {
        $this->locale = $locale;
        
        return $this;
    }

    public function getSlug() : ?string
    {
        return $this->slug;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function getText() : ?string
    {
        return $this->text;
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
    
    public function getPublished() : ?bool
    {
        return $this->enabled;
    }
    
    public function setPublished( ?bool $published ) : PageInterface
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
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType( $type )
    {
        if ( ! in_array( $type, [self::TYPE_SINGLE_PAGE, self::TYPE_MULTI_PAGE] ) ) {
            throw new \InvalidArgumentException( "Invalid Page Type !!!" );
        }
        $this->type = $type;
        
        return $this;
    }
    
    public function getMultipageToc()
    {
        return $this->multipageToc;
    }
    
    public function setMultipageToc( $multipageToc )
    {
        $this->multipageToc = $multipageToc;
        
        return $this;
    }
    
    /*
     * @NOTE: Decalared abstract in TranslatableTrait
     */
    protected function createTranslation() : TranslationInterface
    {
        
    }
}

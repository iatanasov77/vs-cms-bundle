<?php namespace VS\CmsBundle\Model;

use Sylius\Component\Resource\Model\TranslationInterface;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Page implements PageInterface
{
    use TimestampableTrait;
    use ToggleableTrait;    // About enabled field - $enabled (published)
    use TranslatableTrait;
    
    /** @var integer */
    protected $id;

    /** @var string */
    protected $slug;

    /** @var string */
    protected $title;

    /** @var string */
    protected $text;
    
    /** @var Collection|PageCategory[] */
    //protected $categories;
    
    /** @var Collection|PageCategoryRelation[] */
    protected $relations;
    
    /** @var string */
    protected $locale;
    
    public function __construct()
    {
        //$this->categories = new ArrayCollection();
        $this->relations    = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return Collection|PageCategoryRelation[]
     */
    public function getRelations(): Collection
    {
        return $this->relations;
    }
    
    /**
     * @return Collection|PageCategory[]
     */
    public function getCategories()
    {
        //return $this->categories;
        
        $categories = [];
        foreach( $this->getRelations() as $relation ){
            if( ! isset( $categories[$relation->getCategory()->getId()] ) ) {
                $categories[$relation->getCategory()->getId()]    = $relation->getCategory(); //Ensure uniqueness
            }
        }
        
        return $categories;
    }
    
//     public function addCategory( PageCategory $category ) : self
//     {
//         if ( ! $this->categories->contains( $category ) ) {
//             $this->categories[] = $category;
//             $category->addPage( $this );
//         }
//         return $this;
//     }
    
//     public function removeCategory( PageCategory $category ) : self
//     {
//         if ( ! $this->categories->contains( $category ) ) {
//             $this->categories->removeElement( $category );
//             $category->removePage( $this );
//         }
//         return $this;
//     }
    
    public function setTranslatableLocale($locale) : self
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
    
    public function setPublished( ?bool $published ) : self
    {
        $this->enabled = (bool) $published;
        return $this;
    }
    
    /*
     * @NOTE: Decalared abstract in TranslatableTrait
     */
    protected function createTranslation() : TranslationInterface
    {
        
    }
}

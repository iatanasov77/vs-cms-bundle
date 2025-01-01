<?php namespace Vankosoft\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vankosoft\CmsBundle\Model\Interfaces\BannerInterface;
use Vankosoft\CmsBundle\Model\Interfaces\BannerImageInterface;
use Vankosoft\CmsBundle\Model\Interfaces\BannerPlaceInterface;
use Vankosoft\ApplicationBundle\Model\Traits\TranslatableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

class Banner implements BannerInterface
{
    use ToggleableTrait;    // About enabled field - $enabled (public)
    use TranslatableTrait;
    
    /** @var integer */
    protected $id;
    
    /** @var string */
    protected $title;
    
    /** @var string */
    protected $url;
    
    /** @var BannerImageInterface */
    protected $image;
    
    /** @var bool */
    protected $enabled = true;
    
    /** @var integer */
    protected $priority = 0;
    
    /** @var Collection|BannerPlace[] */
    protected $places;
    
    public function __construct()
    {
        $this->fallbackLocale   = 'en_US';
        $this->places           = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    public function setTitle($title): self
    {
        $this->title   = $title;
        
        return $this;
    }
    
    public function getUrl(): ?string
    {
        return $this->url;
    }
    
    public function setUrl( string $url ): self
    {
        $this->url  = $url;
        
        return $this;
    }
    
    public function getImage(): ?BannerImageInterface
    {
        return $this->image;
    }
    
    public function setImage( ?BannerImageInterface $image ): self
    {
        $this->image    = $image;
        
        return $this;
    }
    
    public function isPublic(): bool
    {
        return $this->enabled;
    }
    
    public function isPublished(): bool
    {
        return $this->enabled;
    }
    
    public function getPriority(): int
    {
        return $this->priority;
    }
    
    public function setPriority( int $priority ): self
    {
        $this->priority  = $priority;
        
        return $this;
    }
    
    public function getPlaces(): Collection
    {
        return $this->places;
    }
    
    public function addPlace( BannerPlaceInterface $place ): self
    {
        if ( ! $this->places->contains( $place ) ) {
            $this->places[] = $place;
            $place->addBanner( $this );
        }
        
        return $this;
    }
    
    public function removePlace( BannerPlaceInterface $place ): self
    {
        if ( $this->places->contains( $place ) ) {
            $this->places->removeElement( $place );
            $place->removeBanner( $this );
        }
        
        return $this;
    }
}

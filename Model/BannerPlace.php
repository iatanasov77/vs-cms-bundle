<?php namespace Vankosoft\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vankosoft\ApplicationBundle\Model\Traits\TaxonDescendentTrait;
use Vankosoft\CmsBundle\Model\Interfaces\BannerPlaceInterface;
use Vankosoft\CmsBundle\Model\Interfaces\BannerInterface;

class BannerPlace implements BannerPlaceInterface
{
    use TaxonDescendentTrait;
    
    /** @var integer */
    protected $id;
    
    /** @var string */
    protected $imagineFilter;
    
    /** @var Collection|Banner[] */
    protected $banners;
    
    public function __construct()
    {
        $this->banners  = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getImagineFilter(): string
    {
        return $this->imagineFilter;
    }
    
    public function setImagineFilter( string $imagineFilter ): self
    {
        $this->imagineFilter    = $imagineFilter;
        
        return $this;
    }
    
    public function getPublishedBanners(): Collection
    {
        return $this->getBanners()->filter( function( BannerInterface $banner )
        {
            return $banner->isPublished();
        });
    }
    
    public function getBanners(): Collection
    {
        return $this->banners;
    }
    
    public function addBanner( BannerInterface $banner ): self
    {
        if ( ! $this->banners->contains( $banner ) ) {
            $this->banners[] = $banner;
            $banner->addPlace( $this );
        }
        
        return $this;
    }
    
    public function removeBanner( BannerInterface $banner ): self
    {
        if ( $this->banners->contains( $banner ) ) {
            $this->banners->removeElement( $banner );
            $banner->removePlace( $this );
        }
        
        return $this;
    }
}

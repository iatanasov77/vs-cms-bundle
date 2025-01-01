<?php namespace Vankosoft\CmsBundle\Model;

use Vankosoft\CmsBundle\Model\Interfaces\BannerImageInterface;
use Vankosoft\CmsBundle\Model\Interfaces\BannerInterface;

class BannerImage extends File implements BannerImageInterface
{
    /** @var BannerInterface */
    protected $owner;
    
    public function getBanner(): BannerInterface
    {
        return $this->owner;
    }
    
    public function setBanner( BannerInterface $banner ): self
    {
        $this->setOwner( $banner );
        
        return $this;
    }
}

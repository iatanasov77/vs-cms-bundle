<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Vankosoft\ApplicationBundle\Model\Interfaces\VankosoftCategoryInterface;
use Doctrine\Common\Collections\Collection;

interface PageCategoryInterface extends VankosoftCategoryInterface
{
    public function getPages(): Collection;
    
    public function addPage( PageInterface $page ): self;
    
    public function removePage( PageInterface $page ): self;
}

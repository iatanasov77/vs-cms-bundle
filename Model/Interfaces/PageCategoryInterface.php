<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Vankosoft\ApplicationBundle\Model\Interfaces\VankosoftCategoryInterface;
use Doctrine\Common\Collections\Collection;

interface PageCategoryInterface extends VankosoftCategoryInterface
{
    public function getPages(): Collection;
    
    public function addPage( Page $page ): PageCategoryInterface;
    
    public function removePage( Page $page ): PageCategoryInterface;
}

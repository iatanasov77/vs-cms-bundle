<?php namespace Vankosoft\CmsBundle\Model;

use Vankosoft\ApplicationBundle\Model\Interfaces\VankosoftCategoryInterface;
use Doctrine\Common\Collections\Collection;

interface DocumentCategoryInterface extends VankosoftCategoryInterface
{
    public function getDocuments(): Collection;
    
    public function addDocument( DocumentInterface $document ): DocumentCategoryInterface;
    
    public function removeDocument( DocumentInterface $document ): DocumentCategoryInterface;
}

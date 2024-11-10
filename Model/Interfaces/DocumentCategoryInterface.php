<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Vankosoft\ApplicationBundle\Model\Interfaces\VankosoftCategoryInterface;
use Vankosoft\ApplicationBundle\Model\Interfaces\TaxonDescendentInterface;
use Doctrine\Common\Collections\Collection;

interface DocumentCategoryInterface extends VankosoftCategoryInterface, TaxonDescendentInterface
{
    public function getDocuments(): Collection;
    
    public function addDocument( DocumentInterface $document ): self;
    
    public function removeDocument( DocumentInterface $document ): self;
}

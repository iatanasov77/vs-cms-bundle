<?php namespace Vankosoft\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\Collection;
use Vankosoft\ApplicationBundle\Model\Interfaces\TaxonInterface;

interface DocumentCategoryInterface extends ResourceInterface
{
    public function getDocuments(): Collection;
    
    public function addDocument( DocumentInterface $document ): DocumentCategoryInterface;
    
    public function removeDocument( DocumentInterface $document ): DocumentCategoryInterface;
    
    public function getTaxon(): ?TaxonInterface;
    
    public function setTaxon( ?TaxonInterface $taxon ): void;
}

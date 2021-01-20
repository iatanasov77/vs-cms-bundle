<?php namespace VS\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

interface PageCategoryInterface extends ResourceInterface
{
    public function getPage(): ?PageInterface;
    
    public function setPage( ?PageInterface $page ): void;
    
    public function getTaxon(): ?TaxonInterface;
    
    public function setTaxon( ?TaxonInterface $taxon ): void;
    
    //public function getPosition(): ?int;
    
    //public function setPosition(?int $position): void;
}

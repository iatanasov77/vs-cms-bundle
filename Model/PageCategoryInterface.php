<?php namespace Vankosoft\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\Collection;
use Vankosoft\ApplicationBundle\Model\Interfaces\TaxonInterface;

interface PageCategoryInterface extends ResourceInterface
{
    public function getPages();
    
    public function addPage( Page $page ) : PageCategoryInterface;
    
    public function removePage( Page $page ) : PageCategoryInterface;
    
    public function getTaxon(): ?TaxonInterface;
    
    public function setTaxon( ?TaxonInterface $taxon ): void;
    
    //public function getPosition(): ?int;
    
    //public function setPosition(?int $position): void;
}

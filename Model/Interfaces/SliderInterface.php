<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Sylius\Component\Resource\Model\ResourceInterface;
use Vankosoft\ApplicationBundle\Model\Interfaces\TaxonDescendentInterface;
use Doctrine\Common\Collections\Collection;

interface SliderInterface extends ResourceInterface, TaxonDescendentInterface
{
    public function getItems(): Collection;
    
    public function addItem( SliderItemInterface $item ): self;
    
    public function removeItem( SliderItemInterface $item ): self;
}

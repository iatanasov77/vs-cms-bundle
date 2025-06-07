<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Sylius\Component\Resource\Model\ResourceInterface;
use Vankosoft\ApplicationBundle\Model\Interfaces\TaxonDescendentInterface;
use Doctrine\Common\Collections\Collection;

interface FileManagerInterface extends ResourceInterface, TaxonDescendentInterface
{
    public function getFiles(): Collection;
    
    public function addFile( FileManagerFileInterface $file ): self;
    
    public function removeFile( FileManagerFileInterface $file ): self;
}

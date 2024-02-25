<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\Collection;

interface FileManagerInterface extends ResourceInterface
{
    public function getFiles(): Collection;
    
    public function addFile( FileManagerFileInterface $file ): self;
    
    public function removeFile( FileManagerFileInterface $file ): self;
}

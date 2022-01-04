<?php namespace Vankosoft\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\Collection;

interface FileManagerInterface extends ResourceInterface
{
    public function getFiles();
    
    public function addFile( FileManagerFile $file ) : FileManagerInterface;
    
    public function removeFile( FileManagerFile $file ) : FileManagerInterface;
}

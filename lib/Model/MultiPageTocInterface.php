<?php namespace VS\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface MultiPageTocInterface extends ResourceInterface
{
    public function getTocTitle(): string;
    
    public function getTocRootPage(): TocPageInterface;
}

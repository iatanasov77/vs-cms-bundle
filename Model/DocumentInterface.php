<?php namespace Vankosoft\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface DocumentInterface extends ResourceInterface
{
    public function getTitle() : ?string;
    public function getTocRootPage(): ?TocPageInterface;
}

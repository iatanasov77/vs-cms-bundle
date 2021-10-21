<?php namespace VS\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface DocumentInterface extends ResourceInterface
{
    public function getTitle() : ?string;
    public function getMultipageToc();
}

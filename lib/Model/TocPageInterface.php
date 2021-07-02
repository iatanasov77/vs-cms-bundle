<?php namespace VS\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\Collection;

interface TocPageInterface extends ResourceInterface
{
    public function getTitle(): string
    public function getPage(): ?PageInterface;
    public function getChildren(): Collection;
}

<?php namespace Vankosoft\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\Collection;

interface TocPageInterface extends ResourceInterface
{
    public function getTitle(): ?string;
    public function getDescription(): ?string;
    public function getText(): ?string;
    public function getChildren(): Collection;
}

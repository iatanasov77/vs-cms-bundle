<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Sylius\Component\Resource\Model\ResourceInterface;
use Vankosoft\ApplicationBundle\Model\Interfaces\TranslatableInterface;

interface QuickLinkInterface extends
    ResourceInterface,
    TranslatableInterface
{
    public function getLinkText(): ?string;
    public function getLinkPath(): ?string;
}

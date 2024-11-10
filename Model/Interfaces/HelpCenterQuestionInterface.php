<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Sylius\Component\Resource\Model\ResourceInterface;
use Vankosoft\ApplicationBundle\Model\Interfaces\TranslatableInterface;

interface HelpCenterQuestionInterface extends
    ResourceInterface,
    TranslatableInterface
{
    public function getQuestion(): ?string;
    public function getAnswer(): ?string;
}

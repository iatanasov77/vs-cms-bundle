<?php namespace VS\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use VS\CmsBundle\Model\PageInterface;
use VS\CmsBundle\Model\PageCategoryInterface;

interface PageCategoryRelationInterface extends ResourceInterface
{
    public function getPage() : PageInterface;
    public function getCategory() : PageCategoryInterface;
}

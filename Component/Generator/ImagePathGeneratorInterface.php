<?php namespace VS\CmsBundle\Component\Generator;

use VS\CmsBundle\Model\ImageInterface;

interface ImagePathGeneratorInterface
{
    /**
     * It needs to return a different value on each call, so that consumers don't end up in an infinite loop.
     */
    public function generate(ImageInterface $image): string;
}

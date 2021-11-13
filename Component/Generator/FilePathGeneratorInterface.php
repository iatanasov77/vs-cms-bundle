<?php namespace VS\CmsBundle\Component\Generator;

use VS\CmsBundle\Model\FileInterface;

interface FilePathGeneratorInterface
{
    /**
     * It needs to return a different value on each call, so that consumers don't end up in an infinite loop.
     */
    public function generate(FileInterface $image): string;
}

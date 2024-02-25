<?php namespace Vankosoft\CmsBundle\Component\Generator;

use Vankosoft\CmsBundle\Model\Interfaces\FileInterface;

interface FilePathGeneratorInterface
{
    /**
     * It needs to return a different value on each call, so that consumers don't end up in an infinite loop.
     */
    public function generate( FileInterface $image ): string;
}

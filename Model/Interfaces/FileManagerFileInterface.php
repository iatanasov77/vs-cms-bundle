<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

interface FileManagerFileInterface extends FileInterface
{
    public function getFilemanager(): FileManagerInterface;
    public function setFilemanager( FileManagerInterface $filemanager ): self;
}

<?php namespace VS\CmsBundle\Model;

interface FileManagerFileInterface extends FileInterface
{
    public function getFilemanager(): FileManagerInterface;
    public function setFilemanager( FileManagerInterface $filemanager ): self;
}

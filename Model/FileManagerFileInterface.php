<?php namespace VS\CmsBundle\Model;

interface FileManagerFileInterface extends FileInterface
{
    public function getFilemanager(): string;
    public function setFilemanager( FileManagerInterface $filemanager ): self;
}

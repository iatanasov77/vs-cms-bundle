<?php namespace Vankosoft\CmsBundle\Model\Interfaces;

use Sylius\Component\Resource\Model\ResourceInterface;

interface FileInterface extends ResourceInterface
{
    /**
     * @return string
     */
    public function getType(): ?string;
    
    public function setType(?string $type): void;
    
    public function getFile(): ?\SplFileInfo;
    
    public function setFile(?\SplFileInfo $file): void;
    
    public function hasFile(): bool;
    
    public function getPath(): ?string;
    
    public function setPath(?string $path): void;
    
    public function getOriginalName(): ?string;
    
    public function setOriginalName( string $originalName ): void;
    
    /**
     * @return object
     */
    public function getOwner();
    
    /**
     * @param object|null $owner
     */
    public function setOwner($owner): void;
}

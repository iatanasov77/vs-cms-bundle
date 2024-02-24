<?php namespace Vankosoft\CmsBundle\Model;

use Vankosoft\CmsBundle\Model\Interfaces\FileInterface;

abstract class File implements FileInterface
{
    /** @var mixed */
    protected $id = null;
    
    /**
     * @var string|null
     */
    protected $type;
    
    /**
     * @var \SplFileInfo|null
     */
    protected $file;
    
    /**
     * @var string|null
     */
    protected $path;
    
    /**
     * @var object|null
     */
    protected $owner;
    
    /**
     * @var string|null
     */
    protected $originalName;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getType(): ?string
    {
        return $this->type;
    }
    
    public function setType(?string $type): void
    {
        $this->type = $type;
    }
    
    public function getFile(): ?\SplFileInfo
    {
        return $this->file;
    }
    
    public function setFile(?\SplFileInfo $file): void
    {
        $this->file = $file;
    }
    
    public function hasFile(): bool
    {
        return null !== $this->file;
    }
    
    public function getPath(): ?string
    {
        return $this->path;
    }
    
    public function setPath(?string $path): void
    {
        $this->path = $path;
    }
    
    public function hasPath(): bool
    {
        return null !== $this->path;
    }
    
    public function getOwner()
    {
        return $this->owner;
    }
    
    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }
    
    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }
    
    public function setOriginalName( string $originalName ): void
    {
        $this->originalName = $originalName;
    }
}

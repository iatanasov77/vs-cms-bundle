<?php namespace Vankosoft\CmsBundle\Model;

use Vankosoft\ApplicationBundle\Model\Traits\TranslatableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vankosoft\ApplicationBundle\Model\Interfaces\LoggableObjectInterface;
use Vankosoft\CmsBundle\Model\Interfaces\TocPageInterface;

class TocPage implements TocPageInterface, LoggableObjectInterface
{
    use TranslatableTrait;
    
    /** @var integer */
    protected $id;
    
    /**
     * @var TocPageInterface|null
     */
    protected $root;
    
    /** @var TocPageInterface */
    protected $parent;
    
    /** @var Collection|TocPageInterface[] */
    protected $children;
    
    /** @var DocumentInterface */
    protected $document;
    
    /** @var string */
    protected $slug;
    
    /** @var string */
    protected $title;
    
    /** @var string */
    protected $description;
    
    /** @var string */
    protected $text;
    
    /** @var string */
    protected $locale;
    
    /** @var integer */
    protected $position;
    
    /** @var int|null */
    protected $left;
    
    /** @var int|null */
    protected $right;
    
    /** @var int|null */
    protected $level;
    
    public function __construct()
    {
        $this->fallbackLocale   = 'en_US';
        $this->children = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function isRoot(): bool
    {
        return null === $this->parent;
    }
    
    public function getRoot(): ?TocPageInterface
    {
        return $this->root;
    }
    
    public function setRoot( ?TocPageInterface $root ): self
    {
        $this->root = $root;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getParent(): ?TocPageInterface
    {
        return $this->parent;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setParent(?TocPageInterface $parent): self
    {
        $this->parent = $parent;
        if (null !== $parent) {
            $parent->addChild($this);
        }
        
        return $this;
    }
    
    public function getAncestors(): Collection
    {
        $ancestors = [];
        
        for ( $ancestor = $this->getParent(); null !== $ancestor; $ancestor = $ancestor->getParent() ) {
            $ancestors[] = $ancestor;
        }
        
        return new ArrayCollection( $ancestors );
    }
    
    public function getChildren(): Collection
    {
        return $this->children;
    }
    
    public function hasChild( TocPageInterface $child ): bool
    {
        return $this->children->contains( $child );
    }
    
    public function hasChildren(): bool
    {
        return ! $this->children->isEmpty();
    }
    
    public function addChild( TocPageInterface $child ): void
    {
        if ( ! $this->hasChild( $child ) ) {
            $this->children->add( $child );
        }
        
        if ( $this !== $child->getParent() ) {
            $child->setParent( $this );
        }
    }
    
    public function removeChild( TocPageInterface $child ): void
    {
        if ( $this->hasChild( $child ) ) {
            $child->setParent( null );
            
            $this->children->removeElement( $child );
        }
    }
    
    public function getDocument(): ?DocumentInterface
    {
        return $this->document;
    }
    
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    public function setTitle( $title ): self
    {
        $this->title = $title;
        
        return $this;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    public function setDescription( $description ): self
    {
        $this->description = $description;
        
        return $this;
    }
    
    public function getText(): ?string
    {
        return $this->text;
    }
    
    public function setText( ?string $text ): self
    {
        $this->text = $text;
        
        return $this;
    }
    
    public function getPosition()
    {
        return $this->position;
    }
    
    public function setPosition( $position ): self
    {
        $this->position = $position;
        
        return $this;
    }
    
    public function getLeft(): ?int
    {
        return $this->left;
    }
    
    public function setLeft( ?int $left ): void
    {
        $this->left = $left;
    }
    
    public function getRight(): ?int
    {
        return $this->right;
    }
    
    public function setRight( ?int $right ): void
    {
        $this->right = $right;
    }
    
    public function getLevel(): ?int
    {
        return $this->level;
    }
    
    public function setLevel( ?int $level ): void
    {
        $this->level = $level;
    }
    
    public function getSlug(): ?string
    {
        return $this->slug;
    }
    
    public function setSlug( $slug=null ): void
    {
        $this->slug = $slug;
        //return $this;
    }
    
    /**
     * {@inheritDoc}
     * @see \Vankosoft\ApplicationBundle\Model\Interfaces\LoggableObjectInterface::getTranslatableLocale()
     */
    public function getTranslatableLocale(): ?string
    {
        return $this->locale;
    }
    
    public function setTranslatableLocale($locale): TocPageInterface
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    public function __toString()
    {
        return $this->title;
    }
}

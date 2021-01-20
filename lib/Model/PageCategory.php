<?php namespace VS\CmsBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * Page Category Model
 */
class PageCategory implements PageCategoryInterface
{
    /** @var mixed */
    protected $id;
    
    /** @var PageInterface */
    protected $page;
    
    /** @var TaxonInterface */
    protected $taxon;
    
    /** @var int */
    //protected $position;
    
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getPage(): ?PageInterface
    {
        return $this->page;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setPage(?PageInterface $page): void
    {
        $this->page = $page;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getTaxon(): ?TaxonInterface
    {
        return $this->taxon;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setTaxon(?TaxonInterface $taxon): void
    {
        $this->taxon = $taxon;
    }
    
    /**
     * {@inheritdoc}
     */
//     public function getPosition(): ?int
//     {
//         return $this->position;
//     }
    
    /**
     * {@inheritdoc}
     */
//     public function setPosition(?int $position): void
//     {
//         $this->position = $position;
//     }

    public function __toString()
    {
        return $this->taxon ? $this->taxon->getName() : '';
    }
}

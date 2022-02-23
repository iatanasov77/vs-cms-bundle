<?php namespace Vankosoft\CmsBundle\Repository;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * 
 * Sylius RepositoryInterface contains 3 abstract methods and must therefore be declared abstract 
 * or implement the remaining methods 
 * (
 *      Sylius\Component\Resource\Repository\RepositoryInterface::createPaginator, 
 *      Sylius\Component\Resource\Repository\RepositoryInterface::add, 
 *      Sylius\Component\Resource\Repository\RepositoryInterface::remove
 * )
 *
 */
//class TocPagesRepository extends NestedTreeRepository implements RepositoryInterface
class TocPagesRepository implements RepositoryInterface
{
    /**
     * 
     * This Abstract Methods Copied from Sylius\Component\Resource\Repository\InMemoryRepository
     */
    
    public function createPaginator( array $criteria = [], array $sorting = [] ): iterable
    {
        $resources = $this->findAll();
        
        if ( ! empty( $sorting ) ) {
            //$resources = $this->applyOrder( $resources, $sorting );
        }
        
        if ( ! empty( $criteria ) ) {
            //$resources = $this->applyCriteria($resources, $criteria);
        }
        
        return new Pagerfanta( new ArrayAdapter( $resources ) );
    }
    
    public function add( ResourceInterface $resource ): void
    {
        
    }
    
    public function remove( ResourceInterface $resource ): void
    {
        
    }
}

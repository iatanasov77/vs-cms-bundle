<?php namespace Vankosoft\CmsBundle\Repository;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
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
class TocPagesRepository extends EntityRepository
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
    
    public function insertAfter( ResourceInterface $resource, int $insertAfterId ): bool
    {
        $query      = $this->getEntityManager()->createQuery( 'SELECT tp FROM App\Entity\Cms\TocPage tp WHERE tp.id > ' . $insertAfterId );
        $tocPages   = $query->getResult();
        foreach ( $tocPages as $tp ) {
            $tp->setPosition( $tp->getPosition() - 1 );
            $this->getEntityManager()->persist( $tp );
        }
        $this->getEntityManager()->flush();
        
        return true;
        
        /*
        UPDATE MyTable
            SET `Order` = `Order` + 1
            WHERE `Order` > (SELECT `Order` FROM MyTable WHERE ID=<insert-after-id>);
        
            
        INSERT INTO MyTable (Name, `Order`)
        VALUES (Name, (SELECT `Order` + 1 FROM MyTable WHERE ID = <insert-after-id>));
        */
    }
}

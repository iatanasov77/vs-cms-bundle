<?php namespace Vankosoft\CmsBundle\Repository;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

//class TocPagesRepository extends NestedTreeRepository implements RepositoryInterface
class TocPagesRepository extends EntityRepository
{
    public function insertAfter( ResourceInterface $resource, int $insertAfterId ): bool
    {
        $entityClass    = $this->getEntityName();
        $query          = $this->getEntityManager()->createQuery(
            \sprintf( 'SELECT tp FROM %s tp WHERE tp.id > %s', $entityClass, $insertAfterId )
        );
        
        $tocPages       = $query->getResult();
        foreach ( $tocPages as $tp ) {
            $tp->setPosition( $tp->getPosition() + 1 );
            $this->getEntityManager()->persist( $tp );
        }
        $this->getEntityManager()->flush();
        
        return true;
    }
}

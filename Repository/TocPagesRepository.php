<?php namespace Vankosoft\CmsBundle\Repository;

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;

class TocPagesRepository extends SortableRepository implements RepositoryInterface
{
    use ResourceRepositoryTrait;
    
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

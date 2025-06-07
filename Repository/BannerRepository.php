<?php namespace Vankosoft\CmsBundle\Repository;

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;

class BannerRepository extends SortableRepository implements RepositoryInterface
{
    use ResourceRepositoryTrait;
    
    public function insertAfter( ResourceInterface $resource, int $insertAfterId ): bool
    {
        $entityClass    = $this->getEntityName();
        $query          = $this->getEntityManager()->createQuery(
            \sprintf( 'SELECT b FROM %s b WHERE b.id > %s', $entityClass, $insertAfterId )
        );
        
        $banners    = $query->getResult();
        foreach ( $banners as $b ) {
            $b->setPriority( $b->getPriority() + 1 );
            $this->getEntityManager()->persist( $b );
        }
        $this->getEntityManager()->flush();
        
        return true;
    }
}
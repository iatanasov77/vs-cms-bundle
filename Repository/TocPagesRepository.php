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
        $query      = $this->getEntityManager()->createQuery( 'SELECT tp FROM App\Entity\Cms\TocPage tp WHERE tp.id > ' . $insertAfterId );
        $tocPages   = $query->getResult();
        foreach ( $tocPages as $tp ) {
            $tp->setPosition( $tp->getPosition() + 1 );
            $this->getEntityManager()->persist( $tp );
        }
        $this->getEntityManager()->flush();
        
        return true;
    }
}

<?php namespace Vankosoft\CmsBundle\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository as ResourceRepository;
use Sylius\Component\Resource\Model\ResourceInterface;
use Gedmo\Tree\Entity\Repository\AbstractTreeRepository;

class PageCategoryTreeRepository extends NestedTreeRepository implements RepositoryInterface
{
    protected $resourceRepository;
    
    public function __construct( $em, $class )
    {
        parent::__construct( $em, $class );
        
        $this->resourceRepository = new ResourceRepository( $em, $class );
    }
    
    public function find( $id, $lockMode = null, $lockVersion = null )
    {
        if( ! is_numeric( $id ) ) {
            return $this->findOneBy( ['slug'=>$id] );
        }
        
        return parent::find( $id, $lockMode, $lockVersion );
    }
    
    public function countPages( $categoryId )
    {
        $countPages = $this->find( $categoryId )->getPages()->count();
        
        return $countPages;
    }
    
    public function createPaginator(array $criteria = [], array $sorting = []): iterable
    {
        return $this->resourceRepository( $criteria, $sorting );
    }
    
    public function add( ResourceInterface $resource ): void
    {
        $this->resourceRepository->add( $resource );
    }
    
    public function remove(ResourceInterface $resource ): void
    {
        $this->resourceRepository->remove( $resource );
    }
}

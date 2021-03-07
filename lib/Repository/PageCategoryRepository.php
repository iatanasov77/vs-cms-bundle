<?php namespace VS\CmsBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class PageCategoryRepository extends EntityRepository
{
    public function find( $id, $lockMode = null, $lockVersion = null )
    {
        if( ! is_numeric( $id ) ) {
            return $this->findOneBy( ['slug'=>$id] );
        }
        
        return parent::find( $id, $lockMode, $lockVersion );
    }
}

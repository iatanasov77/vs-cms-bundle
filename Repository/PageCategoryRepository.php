<?php namespace Vankosoft\CmsBundle\Repository;

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
    
    public function findByTaxonId( $taxonId )
    {
        $allCategories  = $this->findAll();
        foreach ( $allCategories as $cat ) {
            if ( $cat->getTaxon()->getId() == $taxonId ) {
                return $cat;
            }
        }
        
        return null;
    }
    
    public function findByTaxonCode( $code )
    {
        $allCategories  = $this->findAll();
        foreach ( $allCategories as $cat ) {
            if ( $cat->getTaxon()->getCode() == $code ) {
                return $cat;
            }
        }
        
        return null;
    }
}

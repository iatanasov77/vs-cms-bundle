<?php namespace Vankosoft\CmsBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class FileManagerRepository extends EntityRepository
{
    public function findByTaxonId( $taxonId )
    {
        $allItems   = $this->findAll();
        foreach ( $allItems as $item ) {
            if ( $item->getTaxon()->getId() == $taxonId ) {
                return $item;
            }
        }
        
        return null;
    }
    
    public function findByTaxonCode( $code )
    {
        $allItems   = $this->findAll();
        foreach ( $allItems as $item ) {
            if ( $item->getTaxon()->getCode() == $code ) {
                return $item;
            }
        }
        
        return null;
    }
}

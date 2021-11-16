<?php namespace VS\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use VS\ApplicationBundle\Controller\AbstractCrudController;
use VS\ApplicationBundle\Controller\TaxonomyHelperTrait;

class VankosoftFileManagerController extends AbstractCrudController
{
    use TaxonomyHelperTrait;
    
    protected function customData( Request $request ): array
    {
        $taxonomy   = $this->get( 'vs_application.repository.taxonomy' )->findByCode(
            $this->getParameter( 'vs_cms.file_manager.taxonomy_code' )
        );
        
        return [
            'taxonomyId'    => $taxonomy ? $taxonomy->getId() : 0,
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $taxonomy   = $this->get( 'vs_application.repository.taxonomy' )->findByCode(
            $this->getParameter( 'vs_cms.file_manager.taxonomy_code' )
        );
        $translatableLocale = $form['currentLocale']->getData();
        $itemName           = $form['title']->getData();
        
        if ( $entity->getTaxon() ) {
            $entity->getTaxon()->setCurrentLocale( $translatableLocale );
            $entity->getTaxon()->setName( $itemName );
            //$entity->getTaxon()->setParent( $taxonomy->getRootTaxon() );
            
        } else {
            /*
             * @WORKAROUND Create Taxon If not exists
             */
            $newTaxon   = $this->createTaxon(
                $itemName,
                $translatableLocale,
                $taxonomy->getRootTaxon(),
                $taxonomy->getId()
            );
            
            $entity->setTaxon( $newTaxon );
        }
    }
}

<?php  namespace VS\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use VS\ApplicationBundle\Controller\AbstractCrudController;

/**
 * Documentation
 * --------------
 * http://atlantic18.github.io/DoctrineExtensions/doc/tree.html
 *
 * Good example
 * -------------
 * http://drib.tech/programming/hierarchical-data-relational-databases-symfony-4-doctrine
 * https://github.com/dribtech/hierarchical-data-tutorial-part-2
 */
class PagesCategoryController extends AbstractCrudController
{
    /*
     *  This Controller Need to be extended into the App/Controller
     */
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        /*
         * @WORKAROUND Create Taxon If not exists
         */
        if ( ! $entity->getTaxon() ) {
            if ( $this->container->hasParameter( 'vs_cms.page_categories.taxonomy_id' ) ) {
                $taxonomyId = $this->getParameter( 'vs_cms.page_categories.taxonomy_id' );
            } else {
                $taxonomyCode   = $this->getParameter( 'vs_application.page_categories.taxonomy_code' );
                $taxonomyId     = $this->get( 'vs_application.repository.taxonomy' )->findByCode( $taxonomyCode )->getId();
            }
 
            $newTaxon   = $this->createTaxon(
                $form['name']->getData(),
                $form['currentLocale']->getData(),
                $entity->getParent() ? $entity->getParent()->getTaxon() : null,
                $taxonomyId
            );
            
            $entity->setTaxon( $newTaxon );
        }
    }
    
    protected function customData(): array
    {
        if ( $this->container->hasParameter( 'vs_cms.page_categories.taxonomy_id' ) ) {
            $taxonomyId = $this->getParameter( 'vs_cms.page_categories.taxonomy_id' );
        } else {
            $taxonomyCode   = $this->getParameter( 'vs_application.page_categories.taxonomy_code' );
            $taxonomyId     = $this->get( 'vs_application.repository.taxonomy' )->findByCode( $taxonomyCode )->getId();
        }
        
        return [
            'taxonomyId'    => $taxonomyId
        ];
    }
}

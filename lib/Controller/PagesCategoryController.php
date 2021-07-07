<?php  namespace VS\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use VS\ApplicationBundle\Controller\AbstractCrudController;
use VS\ApplicationBundle\Controller\TaxonomyHelperTrait;

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
    use TaxonomyHelperTrait;
    
    /*
     *  This Controller Need to be extended into the App/Controller
     */
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        if ( $this->container->hasParameter( 'vs_cms.page_categories.taxonomy_id' ) ) {
            $taxonomyId = $this->getParameter( 'vs_cms.page_categories.taxonomy_id' );
        } else {
            $taxonomyCode   = $this->getParameter( 'vs_application.page_categories.taxonomy_code' );
            $taxonomyId     = $this->get( 'vs_application.repository.taxonomy' )->findByCode( $taxonomyCode )->getId();
        }
        
        $translatableLocale     = $form['currentLocale']->getData();
        $categoryName           = $form['name']->getData();
        $parentCategory         = $this->get( 'vs_cms.repository.page_categories' )
                                        ->findByTaxonId( $_POST['page_category_form']['parent'] );
        
        if ( $entity->getTaxon() ) {
            $entity->getTaxon()->setCurrentLocale( $translatableLocale );
            $entity->getTaxon()->setName( $categoryName );
            if ( $parentCategory ) {
                $entity->getTaxon()->setParent( $parentCategory->getTaxon() );
            }
            
            $entity->setParent( $parentCategory );
        } else {
            /*
             * @WORKAROUND Create Taxon If not exists
             */
            $newTaxon   = $this->createTaxon(
                $categoryName,
                $translatableLocale,
                $parentCategory ? $parentCategory->getTaxon() : null,
                $taxonomyId
            );
            
            $entity->setTaxon( $newTaxon );
            $entity->setParent( $parentCategory );
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

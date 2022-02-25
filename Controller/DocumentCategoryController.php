<?php  namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;
use Vankosoft\ApplicationBundle\Controller\TaxonomyHelperTrait;

class DocumentCategoryController extends AbstractCrudController
{
    use TaxonomyHelperTrait;
    
    protected function customData( Request $request, $entity = null ): array
    {
        $taxonomy   = $this->get( 'vs_application.repository.taxonomy' )->findByCode(
            $this->getParameter( 'vs_application.document_categories.taxonomy_code' )
        );
        
        return [
            'taxonomyId'    => $taxonomy ? $taxonomy->getId() : 0,
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
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
            $taxonomy   = $this->get( 'vs_application.repository.taxonomy' )->findByCode(
                                        $this->getParameter( 'vs_application.page_categories.taxonomy_code' )
                                    );
            $newTaxon   = $this->createTaxon(
                $categoryName,
                $translatableLocale,
                $parentCategory ? $parentCategory->getTaxon() : null,
                $taxonomy->getId()
            );
            
            $entity->setTaxon( $newTaxon );
            $entity->setParent( $parentCategory );
        }
    }
    
    
}

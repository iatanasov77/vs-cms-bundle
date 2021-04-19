<?php namespace VS\CmsBundle\Controller;

use VS\ApplicationBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpFoundation\Request;

class PagesController extends AbstractCrudController
{
    protected function customData(): array
    {
        return [
            'categories'    => $this->get( 'vs_cms.repository.page_categories' )->findAll(),
            'taxonomyId'    => $this->getParameter( 'vs_cms.page_categories.taxonomy_id' )
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $pcr        = $this->get( 'vs_cms.repository.page_categories' );
        
        $formPost = $request->request->get( 'page_form' );
        foreach ( $formPost['category_taxon'] as $taxonId ) {
            $category   = $pcr->findOneBy( ['taxon' => $taxonId] );
            $entity->addCategory( $category );
        }
    }
}
    
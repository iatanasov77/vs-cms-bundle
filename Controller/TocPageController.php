<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;
use Vankosoft\ApplicationBundle\Controller\TaxonomyHelperTrait;

class TocPageController extends AbstractCrudController
{
    use TaxonomyHelperTrait;
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $documentRepository = $this->get( 'vs_cms.repository.document' );
        $rootTocPage        = $documentRepository->find( $request->attributes->get( 'documentId' ) )->getTocRootPage();
        
        if ( ! $entity->getId() ) {
            $this->initNewTocPage( $entity, $form, $request->getLocale() );
        }
        
        $selectedParent = $request->request->get( 'toc_page_form[parent]' );
        if ( $selectedParent ) {
            $parentPage = $this->get( 'vs_cms.repository.toc_page' )->find( $selectedParent );
            $entity->setParent( $parentPage );
        } else {
            $entity->setParent( $rootTocPage );
        }
        
        $entity->setRoot( $rootTocPage );
    }
    
    protected function initNewTocPage( &$tocPage, $form, $locale )
    {
        $title        = $form['title']->getData();
        
        $taxonomy               = $this->get( 'vs_application.repository.taxonomy' )->findByCode(
            $this->getParameter( 'vs_application.document_pages.taxonomy_code' )
        );
        $newTaxon   = $this->createTaxon(
            'TocPage: "' . $title . '"',
            $locale,
            null,
            $taxonomy->getId()
        );
        
        $tocPage->setTaxon( $newTaxon );
    }
}

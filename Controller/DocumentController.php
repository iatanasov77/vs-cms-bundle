<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;
use Vankosoft\ApplicationBundle\Controller\TaxonomyHelperTrait;

class DocumentController extends AbstractCrudController
{
    use TaxonomyHelperTrait;
    
    protected function customData( Request $request, $entity = null ): array
    {
        $taxonomy   = $this->get( 'vs_application.repository.taxonomy' )->findByCode(
            $this->getParameter( 'vs_application.document_pages.taxonomy_code' )
        );
        
        return [
            'taxonomyId'    => $taxonomy ? $taxonomy->getId() : 0,
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $translatableLocale = $form['locale']->getData();
        
        $entity->setTranslatableLocale( $translatableLocale );
        
        if ( ! $entity->getTocRootPage() ) {
            $tocRootPage    = $this->createRootTocPage( $entity, $form );
            $entity->setTocRootPage( $tocRootPage );
        }
    }
    
    protected function createRootTocPage( $entity, $form )
    {
        $translatableLocale     = $form['locale']->getData();
        $rootTocPageName        = $form['title']->getData();
        //$rootTocPageName        = $entity->getTitle()
        $rootTocPageContent     = $form['text']->getData();
        
        $rootTocPage            = $this->get( 'vs_cms.factory.toc_page' )->createNew();
        $taxonomy               = $this->get( 'vs_application.repository.taxonomy' )->findByCode(
            $this->getParameter( 'vs_application.document_pages.taxonomy_code' )
        );
        $newTaxon   = $this->createTaxon(
            'Root TocPage of Document: "' . $rootTocPageName . '"',
            $translatableLocale,
            null,
            $taxonomy->getId()
        );
        
        $rootTocPage->setTaxon( $newTaxon );
        $rootTocPage->setTitle( $rootTocPageName );
        $rootTocPage->setText( $rootTocPageContent );
        
        return $rootTocPage;
    }
}

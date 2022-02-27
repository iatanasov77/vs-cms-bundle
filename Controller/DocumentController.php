<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;
use Vankosoft\ApplicationBundle\Controller\TaxonomyHelperTrait;

class DocumentController extends AbstractCrudController
{
    use TaxonomyHelperTrait;
    
    protected function customData( Request $request, $entity = null ): array
    {
        $rootTocPageText    = $entity && $entity->getTocRootPage() ? $entity->getTocRootPage()->getText() : null;
        
        return [
            'rootTocPageText'   => $rootTocPageText,
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $translatableLocale = $form['locale']->getData();
        $rootTocPageName    = $form['title']->getData();
        $rootTocPageContent = $form['text']->getData();
        
        $entity->setTranslatableLocale( $translatableLocale );
        
        $rootTocPage        = $entity->getTocRootPage();
        if ( ! $rootTocPage ) {
            $rootTocPage    = $this->createRootTocPage( $entity, $form );
        }
        
        $rootTocPage->setTitle( $rootTocPageName );
        $rootTocPage->setText( $rootTocPageContent );
        $entity->setTocRootPage( $rootTocPage );
    }
    
    protected function createRootTocPage( $entity, $form )
    {
        $translatableLocale     = $form['locale']->getData();
        $rootTocPageName        = $form['title']->getData();
        //$rootTocPageName        = $entity->getTitle()
        
        $rootTocPage            = $this->get( 'vs_cms.factory.toc_page' )->createNew();
        $taxonomy               = $this->get( 'vs_application.repository.taxonomy' )->findByCode(
            $this->getParameter( 'vs_application.document_pages.taxonomy_code' )
        );
        $newTaxon   = $this->createTaxon(
            $rootTocPageName,
            $translatableLocale,
            null,
            $taxonomy->getId()
        );
        $newTaxon->setDescription( 'Root TocPage of Document: "' . $rootTocPageName . '"' );
        
        $rootTocPage->setTaxon( $newTaxon );
        
        return $rootTocPage;
    }
}

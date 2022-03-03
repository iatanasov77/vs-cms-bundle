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
            $rootTocPage    = $this->get( 'vs_cms.factory.toc_page' )->createNew();
        }
        
        $rootTocPage->setTranslatableLocale( $translatableLocale );
        $rootTocPage->setTitle( $rootTocPageName );
        $rootTocPage->setDescription( 'Root TocPage of Document: "' . $rootTocPageName . '"' );
        $rootTocPage->setText( $rootTocPageContent );
        
        $entity->setTocRootPage( $rootTocPage );
    }
}

<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;
use Vankosoft\ApplicationBundle\Controller\Traits\TaxonomyHelperTrait;

class DocumentController extends AbstractCrudController
{
    use TaxonomyHelperTrait;
    
    protected function customData( Request $request, $entity = null ): array
    {
        $rootTocPageText        = $entity && $entity->getTocRootPage() ? $entity->getTocRootPage()->getText() : null;
        $tocPagesTranslations   = $this->classInfo['action'] == 'updateAction' ? $this->getTocPagesTranslations() : [];
        
        return [
            'rootTocPageText'       => $rootTocPageText,
            'tocPagesTranslations'  => $tocPagesTranslations,
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request ): void
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
    
    private function getTocPagesTranslations(): array
    {
        $translations   = [];
        $transRepo      = $this->get( 'vs_application.repository.translation' );
        $tocPages       = $this->get( 'vs_cms.repository.toc_page' )->findAll();
        
        foreach ( $tocPages as $page ) {
            $translations[$page->getId()] = array_keys( $transRepo->findTranslations( $page ) );
        }
        
        return $translations;
    }
}

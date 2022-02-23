<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;

class DocumentController extends AbstractCrudController
{
    protected function customData( Request $request, $entity = null ): array
    {
        return [
            
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        //$formData   = $request->request->get( 'taxonomy_form' );
        //$entity->setTocTitle(  );
        
        if ( ! $entity->getTocRootPage() ) {
            $entity->setTocRootPage( $this->createRootTocPage( $entity, $request->getLocale() ) );
        }
    }
    
    protected function createRootTocPage( $entity, $requestLocale )
    {
        //$locale     = $taxonomy->getLocale() ?: $requestLocale;
        $rootTocPage  = $this->get( 'vs_cms.factory.multipage_toc_page' )->createNew();
        
        //$rootTocPage->setCurrentLocale( $locale );
        $rootTocPage->setTitle( 'Root TocPage of MultiPageToc: "' . $entity->getTocTitle() . '"' );
        
        return $rootTocPage;
    }
}

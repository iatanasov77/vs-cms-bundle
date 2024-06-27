<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;

class TocPageController extends AbstractCrudController
{
    protected function customData( Request $request, $entity = null ): array
    {
        $documentRepository = $this->get( 'vs_cms.repository.document' );
        $document           = $documentRepository->find( $request->attributes->get( 'documentId' ) );
        $rootTocPageText    = $document && $document->getTocRootPage() ? $document->getTocRootPage()->getText() : null;
        
        return [
            'rootTocPageText'   => $rootTocPageText,
            'documentId'        => $document ? $document->getId() : 0,
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $documentRepository = $this->get( 'vs_cms.repository.document' );
        $rootTocPage        = $documentRepository->find( $request->attributes->get( 'documentId' ) )->getTocRootPage();
        
        $locale        = $form['locale']->getData();
        $entity->setTranslatableLocale( $locale );
        
        $selectedParent = intval( $request->request->all( 'toc_page_form' )['parent'] );
        if ( $selectedParent ) {
            $parentPage = $this->get( 'vs_cms.repository.toc_page' )->find( $selectedParent );
            $entity->setParent( $parentPage );
        } else {
            $entity->setParent( $rootTocPage );
        }
        
        $entity->setRoot( $rootTocPage );
    }
}

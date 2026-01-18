<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;

class QuickLinkController extends AbstractCrudController
{
    protected function customData( Request $request, $entity = NULL ): array
    {
        $translations   = $this->classInfo['action'] == 'indexAction' ? $this->getTranslations() : [];
        
        return [
            'translations'      => $translations,
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $formPost   = $request->request->all( 'quick_link_form' );
        $formLocale = $formPost['locale'];
        
        if ( $formLocale ) {
            $entity->setTranslatableLocale( $formLocale );
        }
    }
}
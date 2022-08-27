<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;

class VankosoftFileManagerFileController extends AbstractCrudController
{
    protected function customData( Request $request, $entity = null ): array
    {
        return [
            
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        
    }
}

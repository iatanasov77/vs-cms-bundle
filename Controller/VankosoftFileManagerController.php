<?php namespace VS\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use VS\ApplicationBundle\Controller\AbstractCrudController;

class VankosoftFileManagerController extends AbstractCrudController
{
    protected function customData( Request $request ): array
    {
        return [
            
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        
    }
}

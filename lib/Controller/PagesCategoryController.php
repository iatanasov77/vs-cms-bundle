<?php  namespace VS\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use VS\ApplicationBundle\Controller\AbstractCrudController;

/**
 * Documentation
 * --------------
 * http://atlantic18.github.io/DoctrineExtensions/doc/tree.html
 *
 * Good example
 * -------------
 * http://drib.tech/programming/hierarchical-data-relational-databases-symfony-4-doctrine
 * https://github.com/dribtech/hierarchical-data-tutorial-part-2
 */
class PagesCategoryController extends AbstractCrudController
{
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $entity->setTranslatableLocale( $form['locale']->getData() );
    }
    
    protected function customData(): array
    {
        return [
            // This Controller Need to be extended into the App/Controller
            'taxonomyId'    => 0
        ];
    }
}

<?php namespace VS\CmsBundle\Controller;

use VS\ApplicationBundle\Controller\AbstractCrudController;

class PagesController extends AbstractCrudController
{
    protected function customData(): array
    {
        return [
            'categories'    => $this->get( 'vs_cms.repository.page_categories' )->findAll()
        ];
    }
}
    
<?php namespace VS\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use VS\ApplicationBundle\Controller\AbstractCrudController;

class PagesController extends AbstractCrudController
{
    protected function customData(): array
    {
        return [
            'categories'    => $this->get( 'vs_cms.repository.page_categories' )->findAll(),
            'taxonomyId'    => $this->getParameter( 'vs_cms.page_categories.taxonomy_id' )
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $categories = new ArrayCollection();
        $pcr        = $this->get( 'vs_cms.repository.page_categories' );
        $formPost   = $request->request->get( 'page_form' );
        
        if ( isset( $formPost['locale'] ) ) {
            $entity->setTranslatableLocale( $formPost['locale'] );
        }
        
        if ( isset( $formPost['category_taxon'] ) ) {
            foreach ( $formPost['category_taxon'] as $taxonId ) {
                $category       = $pcr->findOneBy( ['taxon' => $taxonId] );
                if ( $category ) {
                    $categories[]   = $category;
                    $entity->addCategory( $category );
                }
            }
            
            foreach ( $entity->getCategories() as $cat ) {
                if ( ! $categories->contains( $cat ) ) {
                    $entity->removeCategory( $cat );
                }
            }
        }
    }
}

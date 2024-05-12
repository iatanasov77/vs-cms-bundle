<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;
use Vankosoft\ApplicationBundle\Controller\Traits\TaxonomyHelperTrait;
use Vankosoft\ApplicationBundle\Controller\Traits\FilterFormTrait;

use Vankosoft\CmsBundle\Form\ClonePageForm;
use Vankosoft\CmsBundle\Form\PreviewPageForm;

class PagesController extends AbstractCrudController
{
    use TaxonomyHelperTrait;
    use FilterFormTrait;
    
    protected function customData( Request $request, $entity = null ): array
    {
        $translations   = $this->classInfo['action'] == 'indexAction' ? $this->getTranslations() : [];
        $versions       = $this->classInfo['action'] == 'indexAction' ? $this->getVersions( $translations ) : [];
        
        $taxonomy       = $this->getTaxonomy( 'vs_application.page_categories.taxonomy_code' );
        $tagsContext    = $this->get( 'vs_application.repository.tags_whitelist_context' )->findByTaxonCode( 'static-pages' );
        
        $categoryClass  = $this->getParameter( 'vs_cms.model.page_categories.class' );
        $filterCategory = $request->attributes->get( 'filterCategory' );
        $filterForm     = $this->getFilterForm( $categoryClass, $filterCategory, $request );
        
        if ( $filterCategory ) {
            var_dump( $filterCategory ); die;
            $movies         = $paginator->paginate(
                $paginatorItems,
                $request->query->getInt( 'page', 1 ) /*page number*/,
                $this->moviesPerPage /*limit per page*/
            );
        }
        
        return [
            'items'             => $this->getRepository()->findAll(),
            'taxonomyId'        => $taxonomy ? $taxonomy->getId() : 0,
            'translations'      => $translations,
            'versions'          => $versions,
            
            'formClone'         => $this->createForm( ClonePageForm::class )->createView(),
            'formPreview'       => $this->createForm( PreviewPageForm::class )->createView(),
            
            'pageTags'          => $tagsContext->getTagsArray(),
            
            'filterForm'        => $filterForm->createView(),
            'filterCategory'    => $filterCategory,
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $categories = new ArrayCollection();
        $pcr        = $this->get( 'vs_cms.repository.page_categories' );
        
        $formPost   = $request->request->all( 'page_form' );
        $formLocale = $formPost['locale'];
        
        if ( $formLocale ) {
            $entity->setTranslatableLocale( $formLocale );
        }
        
        $selectedCategories = \json_decode( $request->request->get( 'selectedCategories' ), true );
        $this->buildCategories( $entity, $selectedCategories );
    }
    
    protected function getFilterRepository()
    {
        return $this->get( 'vs_cms.repository.page_categories' );
    }
    
    private function getTranslations()
    {
        $translations   = [];
        $transRepo      = $this->get( 'vs_application.repository.translation' );
        
        foreach ( $this->getRepository()->findAll() as $page ) {
            //$translations[$page->getId()] = \array_keys( $transRepo->findTranslations( $page ) );
            $translations[$page->getId()] = \array_reverse( \array_keys( $transRepo->findTranslations( $page ) ) );
        }
        
        return $translations;
    }
    
    private function getVersions( $translations )
    {
        $versions   = [];
        $logRepo    = $this->get( 'vs_application.repository.logentry' );
        
        foreach ( $translations as $pageId => $locales ) {
            foreach ( $locales as $locale ) {
                $currentVersion = $this->getRepository()->getCurrentVersion( $pageId, $locale, $logRepo );
                if ( $currentVersion ) {
                    $versions[$pageId][$locale] = $currentVersion;
                }
            }
        }
        
        return $versions;
    }
    
    private function buildCategories( &$entity, array $categories )
    {
        $repo   = $this->get( 'vs_cms.repository.page_categories' );
        
        $entity->setCategories( new ArrayCollection() );
        foreach ( $categories as $c ) {
            $entity->addCategory( $repo->find( $c['id'] ) );
        }
    }
}

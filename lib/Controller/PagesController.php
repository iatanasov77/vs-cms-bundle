<?php namespace VS\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use VS\ApplicationBundle\Controller\AbstractCrudController;

class PagesController extends AbstractCrudController
{
    protected function customData(): array
    {
        $translations   = $this->classInfo['action'] == 'indexAction' ? $this->getTranslations() : [];
        $versions       = $this->classInfo['action'] == 'indexAction' ? $this->getVersions( $translations ) : [];
        
        if ( $this->container->hasParameter( 'vs_cms.page_categories.taxonomy_id' ) ) {
            $taxonomyId = $this->getParameter( 'vs_cms.page_categories.taxonomy_id' );
        } else {
            $taxonomyCode   = $this->getParameter( 'vs_application.page_categories.taxonomy_code' );
            $taxonomyId     = $this->get( 'vs_application.repository.taxonomy' )->findByCode( $taxonomyCode )->getId();
        }
        
        return [
            'categories'    => $this->get( 'vs_cms.repository.page_categories' )->findAll(),
            'taxonomyId'    => $taxonomyId,
            'translations'  => $translations,
            'versions'      => $versions,
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
    
    private function getTranslations()
    {
        $translations   = [];
        $transRepo      = $this->get( 'vs_application.repository.translation' );
        
        foreach ( $this->getRepository()->findAll() as $page ) {
            $translations[$page->getId()] = array_keys( $transRepo->findTranslations( $page ) );
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
}

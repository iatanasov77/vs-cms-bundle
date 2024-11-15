<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;
use Vankosoft\ApplicationBundle\Controller\Traits\TaxonomyHelperTrait;

class BannerPlaceController extends AbstractCrudController
{
    use TaxonomyHelperTrait;
    
    protected function customData( Request $request, $entity = NULL ): array
    {
        $taxonomy       = $this->getTaxonomy( 'vs_application.banner_places.taxonomy_code' );
        
        $translations   = $this->classInfo['action'] == 'indexAction' ? $this->getTranslations( false ) : [];
        if ( $entity && $entity->getTaxon() ) {
            $entity->getTaxon()->setCurrentLocale( $request->getLocale() );
        }
        
        $bannersTranslations    = $this->classInfo['action'] == 'updateAction' ? $this->getBannersTranslations() : [];
        
        return [
            'taxonomyId'            => $taxonomy ? $taxonomy->getId() : 0,
            'translations'          => $translations,
            'bannersTranslations'   => $bannersTranslations,
            'items'                 => $this->getRepository()->findAll(),
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $translatableLocale     = $form['currentLocale']->getData();
        $this->get( 'vs_application.slug_generator' )->setLocaleCode( $translatableLocale );
        
        $bannerPlaceName        = $form['name']->getData();
        
        if ( $entity->getTaxon() ) {
            $entityTaxon    = $entity->getTaxon();
            
            $entityTaxon->getTranslation( $translatableLocale );
            $entityTaxon->setCurrentLocale( $translatableLocale );
            $request->setLocale( $translatableLocale );
            if ( ! in_array( $translatableLocale, $entityTaxon->getExistingTranslations() ) ) {
                $taxonTranslation   = $this->createTranslation( $entityTaxon, $translatableLocale, $bannerPlaceName );
                
                $entityTaxon->addTranslation( $taxonTranslation );
            } else {
                $taxonTranslation   = $entityTaxon->getTranslation( $translatableLocale );
                
                $taxonTranslation->setName( $bannerPlaceName );
                $taxonTranslation->setSlug( $this->get( 'vs_application.slug_generator' )->generate( $bannerPlaceName ) );
            }
        } else {
            /*
             * @WORKAROUND Create Taxon If not exists
             */
            $taxonomy   = $this->getTaxonomy( 'vs_application.banner_places.taxonomy_code' );
            $newTaxon   = $this->createTaxon(
                $bannerPlaceName,
                $translatableLocale,
                null,
                $taxonomy->getId()
            );
            
            $entity->setTaxon( $newTaxon );
        }
    }
    
    private function getBannersTranslations(): array
    {
        $translations   = [];
        $transRepo      = $this->get( 'vs_application.repository.translation' );
        $banners        = $this->get( 'vs_cms.repository.banner' )->findAll();
        
        foreach ( $banners as $item ) {
            $translations[$item->getId()] = array_keys( $transRepo->findTranslations( $item ) );
        }
        
        return $translations;
    }
}

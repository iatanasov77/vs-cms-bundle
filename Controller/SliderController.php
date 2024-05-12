<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;
use Vankosoft\ApplicationBundle\Controller\Traits\TaxonomyHelperTrait;

class SliderController extends AbstractCrudController
{
    use TaxonomyHelperTrait;
    
    protected function customData( Request $request, $entity = NULL ): array
    {
        $taxonomy       = $this->getTaxonomy( 'vs_application.sliders.taxonomy_code' );
        
        $translations   = $this->classInfo['action'] == 'indexAction' ? $this->getTranslations( false ) : [];
        if ( $entity && $entity->getTaxon() ) {
            $entity->getTaxon()->setCurrentLocale( $request->getLocale() );
        }
        
        $sliderItemsTranslations    = $this->classInfo['action'] == 'updateAction' ? $this->getSliderItemsTranslations() : [];
        
        return [
            'taxonomyId'                => $taxonomy ? $taxonomy->getId() : 0,
            'translations'              => $translations,
            'sliderItemsTranslations'   => $sliderItemsTranslations,
            'items'                     => $this->getRepository()->findAll(),
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $translatableLocale     = $form['currentLocale']->getData();
        $this->get( 'vs_application.slug_generator' )->setLocaleCode( $translatableLocale );
        
        $sliderName           = $form['name']->getData();
        
        if ( $entity->getTaxon() ) {
            $entityTaxon    = $entity->getTaxon();
            
            $entityTaxon->getTranslation( $translatableLocale );
            $entityTaxon->setCurrentLocale( $translatableLocale );
            $request->setLocale( $translatableLocale );
            if ( ! in_array( $translatableLocale, $entityTaxon->getExistingTranslations() ) ) {
                $taxonTranslation   = $this->createTranslation( $entityTaxon, $translatableLocale, $sliderName );
                
                $entityTaxon->addTranslation( $taxonTranslation );
            } else {
                $taxonTranslation   = $entityTaxon->getTranslation( $translatableLocale );
                
                $taxonTranslation->setName( $sliderName );
                $taxonTranslation->setSlug( $this->get( 'vs_application.slug_generator' )->generate( $sliderName ) );
            }
        } else {
            /*
             * @WORKAROUND Create Taxon If not exists
             */
            $taxonomy   = $this->getTaxonomy( 'vs_application.sliders.taxonomy_code' );
            $newTaxon   = $this->createTaxon(
                $sliderName,
                $translatableLocale,
                null,
                $taxonomy->getId()
            );
            
            $entity->setTaxon( $newTaxon );
        }
    }
    
    private function getSliderItemsTranslations(): array
    {
        $translations   = [];
        $transRepo      = $this->get( 'vs_application.repository.translation' );
        $sliderItems    = $this->get( 'vs_cms.repository.slider_item' )->findAll();
        
        foreach ( $sliderItems as $item ) {
            $translations[$item->getId()] = array_keys( $transRepo->findTranslations( $item ) );
        }
        
        return $translations;
    }
}

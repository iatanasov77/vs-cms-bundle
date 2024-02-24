<?php namespace Vankosoft\ApplicationBundle\Controller;

use Vankosoft\ApplicationBundle\Model\Interfaces\TaxonomyInterface;
use Vankosoft\ApplicationBundle\Component\Exception\TaxonomyNotFoundException;

trait TaxonomyHelperTrait
{
    protected function getTaxonomy( string $taxonomyCodeParameter ): TaxonomyInterface
    {
        $taxonomyCode   = $this->getParameter( $taxonomyCodeParameter );
        $taxonomy       = $this->get( 'vs_application.repository.taxonomy' )->findByCode( $taxonomyCode );
        if ( ! $taxonomy ) {
            $message    = \sprintf( 'Taxonomy with code "%s" Not Exists. Please create it before!', $taxonomyCode );
            throw new TaxonomyNotFoundException( $message );
        }
        
        return $taxonomy;
    }
    
    protected function createTaxon( $name, $locale, $parent, $taxonomyId )
    {
        $taxon  = $this->get( 'vs_application.factory.taxon' )->createNew();
        
        $taxon->setCurrentLocale( $locale );
        $taxon->setName( $name );
        
        $slug   = $this->createTaxonCode( $name );
        
        $taxon->setCode( $slug );
        $taxon->setSlug( $slug );
        
        if ( ! $parent ) {
            $taxonomy   = $this->get( 'vs_application.repository.taxonomy' )->find( $taxonomyId );
            $parent     = $taxonomy->getRootTaxon();
        }
        $taxon->setParent( $parent );
        
        return $taxon;
    }
    
    protected function createTaxonCode( $taxonName )
    {
        $code           = $this->get( 'vs_application.slug_generator' )->generate( $taxonName );
        $useThisCode    = $code;
        $i              = 0;
        
        while( $this->get( 'vs_application.repository.taxon' )->findByCode( $useThisCode ) ) {
            $i++;
            $useThisCode    = $code . '-' . $i;
        }
        
        return $useThisCode;
    }
    
    protected function createTranslation( $taxon, $locale, $name, $description = null )
    {
        $translation    = $taxon->createNewTranslation();
        
        $translation->setLocale( $locale );
        $translation->setName( $name );
        $translation->setDescription( $description );
        $translation->setSlug( $this->get( 'vs_application.slug_generator' )->generate( $name ) );
        
        return $translation;
    }
    
    protected function getTranslations( bool $paginated = true )
    {
        $locales        = $this->get( 'vs_application.repository.locale' )->findAll();
        
        $resources      = $paginated ? $this->resources->getCurrentPageResults() : $this->getRepository()->findAll();
        $translations   = [];
        foreach ( $resources as $category ) {
            foreach( $locales as $locale ) {
                $category->getTaxon()->getTranslation( $locale->getCode() );
            }
            
            $translations[$category->getId()] = $category->getTaxon()->getExistingTranslations();
        }
        //echo "<pre>"; var_dump( $translations ); die;
        
        return $translations;
    }
}

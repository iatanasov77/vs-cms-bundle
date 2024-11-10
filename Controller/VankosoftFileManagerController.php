<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;
use Vankosoft\ApplicationBundle\Controller\Traits\TaxonomyHelperTrait;

class VankosoftFileManagerController extends AbstractCrudController
{
    use TaxonomyHelperTrait;
    
    protected function customData( Request $request, $entity = null ): array
    {
        $taxonomy   = $this->getTaxonomy( 'vs_cms.file_manager.taxonomy_code' );
        
        $fileManagerFiles   = [];
        if ( $entity ) {
            $filesystem = $this->get( 'knp_gaufrette.filesystem_map' )->get( $this->getParameter( 'vs_cms.gaufrette.filemanager.filesystem' ) );
            
            foreach( $entity->getFiles() as $file ) {
                if ( ! empty( $file->getPath() ) ) {
                    $fileManagerFiles[] = [
                        'gaufrette_file'    => $filesystem->get( $file->getPath() ),
                        'metadata'          => [
                            'original_name' => $file->getOriginalName(),
                            // dimension is false if not an image
                            'dimension'     => @getimagesize( 
                                $this->getParameter( 'vs_cms.filemanager_shared_media_gaufrette.filemanager' ) . $file->getPath()
                            ),
                        ],
                    ];
                }
            }
        }
        
        return [
            'taxonomyId'        => $taxonomy ? $taxonomy->getId() : 0,
            'fileManagerFiles'  => $fileManagerFiles,
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $taxonomy           = $this->getTaxonomy( 'vs_cms.file_manager.taxonomy_code' );
        $translatableLocale = $form['currentLocale']->getData();
        $itemName           = $form['title']->getData();
        
        if ( $entity->getTaxon() ) {
            $entity->getTaxon()->setCurrentLocale( $translatableLocale );
            $entity->getTaxon()->setName( $itemName );
            //$entity->getTaxon()->setParent( $taxonomy->getRootTaxon() );
            
        } else {
            /*
             * @WORKAROUND Create Taxon If not exists
             */
            $newTaxon   = $this->createTaxon(
                $itemName,
                $translatableLocale,
                $taxonomy->getRootTaxon(),
                $taxonomy->getId()
            );
            
            $entity->setTaxon( $newTaxon );
        }
    }
}

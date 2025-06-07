<?php namespace Vankosoft\CmsBundle\Form\Traits;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait FosCKEditor4Config
{
    protected function configureCkEditorOptions( OptionsResolver &$resolver ): void
    {
        $resolver
            ->setDefaults([
                'ckeditor_uiColor'              => '#ffffff',
                'ckeditor_toolbar'              => 'full',
                'ckeditor_extraPlugins'         => '',
                'ckeditor_removeButtons'        => '',
                'ckeditor_allowedContent'       => false,
                'ckeditor_extraAllowedContent'  => '*[*]{*}(*)',
            ])
            
            ->setDefined([
                'ckeditor_uiColor',
                'ckeditor_toolbar',
                'ckeditor_extraPlugins',
                'ckeditor_removeButtons',
                'ckeditor_allowedContent',
                'ckeditor_extraAllowedContent',
            ])
            
            ->setAllowedTypes( 'ckeditor_uiColor', 'string' )
            ->setAllowedTypes( 'ckeditor_toolbar', 'string' )
            ->setAllowedTypes( 'ckeditor_extraPlugins', 'string' )
            ->setAllowedTypes( 'ckeditor_removeButtons', 'string' )
            ->setAllowedTypes( 'ckeditor_allowedContent', ['boolean', 'string'] )
            ->setAllowedTypes( 'ckeditor_extraAllowedContent', 'string' )
        ;
    }
    
    protected function ckEditorConfig( array $options ): array
    {
        $ckEditorConfig = [
            'uiColor'                           => $options['ckeditor_uiColor'],
            'toolbar'                           => $options['ckeditor_toolbar'],
            'extraPlugins'                      => array_map( 'trim', explode( ',', $options['ckeditor_extraPlugins'] ) ),
            'removeButtons'                     => $options['ckeditor_removeButtons'],
            
            //'filebrowserBrowseRoute'            => 'file_manager',
            'filebrowserBrowseRoute'            => 'vs_cms_fosckeditor_browse',
            'filebrowserBrowseRouteParameters'  => ['conf' => 'default', 'directory' => '1', 'module' => 'ckeditor'],
            'filebrowserBrowseRouteType'        => UrlGeneratorInterface::ABSOLUTE_URL,
            
            //'filebrowserUploadRoute'            => 'file_manager_upload',
            'filebrowserUploadRoute'            => 'vs_cms_fosckeditor_upload',
            'filebrowserUploadRouteParameters'  => ['conf' => 'default', 'directory' => '1', 'module' => 'ckeditor'],
        ];
        
        $ckEditorAllowedContent = (bool)$options['ckeditor_allowedContent'];
        if ( $ckEditorAllowedContent ) {
            $ckEditorConfig['allowedContent']       = $ckEditorAllowedContent;
        } else {
            $ckEditorConfig['extraAllowedContent']  = $options['ckeditor_extraAllowedContent'];
        }
        
        return $ckEditorConfig;
    }
}
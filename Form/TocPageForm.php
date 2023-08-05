<?php namespace Vankosoft\CmsBundle\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Component\Resource\Repository\RepositoryInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

use Doctrine\ORM\EntityRepository;

class TocPageForm extends AbstractForm
{
    public function __construct(
        string $dataClass,
        RepositoryInterface $localesRepository,
        RequestStack $requestStack
    ) {
        parent::__construct( $dataClass );
        
        $this->localesRepository    = $localesRepository;
        $this->requestStack         = $requestStack;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        parent::buildForm( $builder, $options );
        
        $entity         = $builder->getData();
        $currentLocale  = $entity->getTranslatableLocale() ?: $this->requestStack->getCurrentRequest()->getLocale();
        
        $builder
            ->add( 'locale', ChoiceType::class, [
                'label'                 => 'vs_cms.form.locale',
                'translation_domain'    => 'VSCmsBundle',
                'choices'               => \array_flip( $this->fillLocaleChoices() ),
                'data'                  => $currentLocale,
                'mapped'                => false,
            ])
        
            ->add( 'parent', EntityType::class, [
                'required'              => false,
                'label'                 => 'vs_cms.form.parent',
                'translation_domain'    => 'VSCmsBundle',
                'class'                 => $this->dataClass,
                'choice_label'          => 'title',
                'placeholder'           => 'vs_cms.form.toc_page.parent_page_placeholder',
                'query_builder'         => function ( EntityRepository $er ) use ( $options )
                {
                    //var_dump( $er ); die;
                    return $er->createQueryBuilder( 't' )
                            ->where( 't.root = :tocRootPage' )
                            ->setParameter( 'tocRootPage', $options['tocRootPage'] );
                }
            ])
            
            ->add( 'title', TextType::class, [
                'label'                 => 'vs_cms.form.title',
                'translation_domain'    => 'VSCmsBundle',
                
            ])
            
            ->add( 'description', TextType::class, [
                'required'              => false,
                'label'                 => 'vs_cms.form.description',
                'translation_domain'    => 'VSCmsBundle',
                
            ])
            
            ->add( 'text', CKEditorType::class, [
                'label'                 => 'vs_cms.form.page.page_content',
                'translation_domain'    => 'VSCmsBundle',
                'config'                => [
                    'uiColor'                           => $options['ckeditor_uiColor'],
                    'extraAllowedContent'               => $options['ckeditor_extraAllowedContent'],
                    
                    'toolbar'                           => $options['ckeditor_toolbar'],
                    //'extraPlugins'                      => array_map( 'trim', explode( ',', $options['ckeditor_extraPlugins'] ) ),
                    'extraPlugins'                      => $options['ckeditor_extraPlugins'],
                    'removeButtons'                     => $options['ckeditor_removeButtons'],
                    
                    'filebrowserBrowseRoute'            => 'vs_cms_fosckeditor_browse',
                    'filebrowserBrowseRouteParameters'  => ['conf' => 'default', 'directory' => '1'],
                    'filebrowserBrowseRouteType'        => 0,
                    'filebrowserUploadRoute'            => 'vs_cms_fosckeditor_upload',
                    'filebrowserUploadRouteParameters'  => ['conf' => 'default', 'directory' => '1'],
                ],
                'required'              => false,
            ])
        ;
    }

    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefaults([
                'csrf_protection'   => false,
                'tocRootPage'       => null,
                
                // CKEditor Options
                'ckeditor_uiColor'              => '#ffffff',
                'ckeditor_extraAllowedContent'  => '*[*]{*}(*)',
                
                'ckeditor_toolbar'              => 'full',
                'ckeditor_extraPlugins'         => [],
                'ckeditor_removeButtons'        => ''
            ])
            
            ->setDefined([
                'page',
                
                // CKEditor Options
                'ckeditor_uiColor',
                'ckeditor_extraAllowedContent',
                'ckeditor_toolbar',
                'ckeditor_extraPlugins',
                'ckeditor_removeButtons',
            ])
            
            ->setAllowedTypes( 'ckeditor_uiColor', 'string' )
            ->setAllowedTypes( 'ckeditor_extraAllowedContent', 'string' )
            ->setAllowedTypes( 'ckeditor_toolbar', 'string' )
            ->setAllowedTypes( 'ckeditor_extraPlugins', 'string[]' )
            ->setAllowedTypes( 'ckeditor_removeButtons', 'string' )
        ;
    }
    
    public function getName()
    {
        return 'vs_cms.toc_page';
    }
}

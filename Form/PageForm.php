<?php namespace Vankosoft\CmsBundle\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Component\Resource\Repository\RepositoryInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Vankosoft\CmsBundle\Model\Page;
use Vankosoft\CmsBundle\Model\PageInterface;

class PageForm extends AbstractForm
{
    /** @var string */
    protected $categoryClass;
    
    public function __construct(
        string $dataClass,
        RepositoryInterface $localesRepository,
        RequestStack $requestStack,
        string $categoryClass
    ) {
        parent::__construct( $dataClass );
        
        $this->localesRepository    = $localesRepository;
        $this->requestStack         = $requestStack;
        
        $this->categoryClass        = $categoryClass;
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
            
            ->add( 'enabled', CheckboxType::class, [
                'label' => 'vs_cms.form.page.published',
                'translation_domain'    => 'VSCmsBundle',
            ])  
            
            ->add( 'category_taxon', ChoiceType::class, [
                'label'                 => 'vs_cms.form.page.categories',
                'translation_domain'    => 'VSCmsBundle',
                'multiple'              => true,
                'required'              => false,
                'mapped'                => false,
                'placeholder'           => 'vs_cms.form.page.categories_placeholder',
            ])
            
            ->add( 'description', TextType::class, [
                'label'                 => 'vs_cms.form.description',
                'translation_domain'    => 'VSCmsBundle',
            ])
            ->add( 'title', TextType::class, [
                'label'                 => 'vs_cms.form.title',
                'translation_domain'    => 'VSCmsBundle',
            ])
            ->add( 'tagsInputWhitelist', HiddenType::class, ['mapped' => false] )
            ->add( 'tags', TextType::class, [
                'label'                 => 'vs_vvp.form.video.tags',
                'translation_domain'    => 'VanzVideoPlayer',
            ])
            ->add( 'slug', TextType::class, [
                'label'                 => 'vs_cms.form.page.slug',
                'translation_domain'    => 'VSCmsBundle',
            ])
            
            ->add( 'text', CKEditorType::class, [
                'label'                 => 'vs_cms.form.page.page_content',
                'translation_domain'    => 'VSCmsBundle',
                'config'                => $this->ckEditorConfig( $options ),
            ])
        ;
    }

    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefaults([
                'csrf_protection'   => false,
                
                // CKEditor Options
                'ckeditor_uiColor'              => '#ffffff',
                'ckeditor_toolbar'              => 'full',
                'ckeditor_extraPlugins'         => '',
                'ckeditor_removeButtons'        => '',
                'ckeditor_allowedContent'       => false,
                'ckeditor_extraAllowedContent'  => '*[*]{*}(*)',
            ])
            
            ->setDefined([
                'page',
                
                // CKEditor Options
                'ckeditor_uiColor',
                'ckeditor_toolbar',
                'ckeditor_extraPlugins',
                'ckeditor_removeButtons',
                'ckeditor_allowedContent',
                'ckeditor_extraAllowedContent',
            ])
            
            ->setAllowedTypes( 'page', PageInterface::class )
            ->setAllowedTypes( 'ckeditor_uiColor', 'string' )
            ->setAllowedTypes( 'ckeditor_toolbar', 'string' )
            ->setAllowedTypes( 'ckeditor_extraPlugins', 'string' )
            ->setAllowedTypes( 'ckeditor_removeButtons', 'string' )
            ->setAllowedTypes( 'ckeditor_allowedContent', ['boolean', 'string'] )
            ->setAllowedTypes( 'ckeditor_extraAllowedContent', 'string' )
        ;
    }
    
    public function getName()
    {
        return 'vs_cms.page';
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
            'filebrowserBrowseRouteParameters'  => ['conf' => 'default', 'directory' => '1'],
            'filebrowserBrowseRouteType'        => 0,
            //'filebrowserUploadRoute'            => 'file_manager_upload',
            'filebrowserUploadRoute'            => 'vs_cms_fosckeditor_upload',
            'filebrowserUploadRouteParameters'  => ['conf' => 'default', 'directory' => '1'],
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


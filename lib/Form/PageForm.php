<?php namespace VS\CmsBundle\Form;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use VS\CmsBundle\Model\PageInterface;

class PageForm extends AbstractResourceType
{
    protected $requestStack;
    
    protected $categoryClass;
    
    public function __construct( RequestStack $requestStack, string $dataClass, string $categoryClass )
    {
        parent::__construct( $dataClass );
        
        $this->requestStack     = $requestStack;
        $this->categoryClass    = $categoryClass;
    }

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $entity         = $builder->getData();
        $currentLocale  = $entity->getTranslatableLocale() ?: $this->requestStack->getCurrentRequest()->getLocale();
        
        $builder
            ->add( 'locale', ChoiceType::class, [
                'label'                 => 'vs_cms.form.locale',
                'translation_domain'    => 'VSCmsBundle',
                'choices'               => \array_flip( \VS\ApplicationBundle\Component\I18N::LanguagesAvailable() ),
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
            
            ->add( 'title', TextType::class, [
                'label'                 => 'vs_cms.form.title',
                'translation_domain'    => 'VSCmsBundle',
            ])
            ->add( 'slug', TextType::class, [
                'label'                 => 'vs_cms.form.page.slug',
                'translation_domain'    => 'VSCmsBundle',
            ])
            
            ->add( 'text', CKEditorType::class, [
                'label'                 => 'vs_cms.form.page.page_content',
                'translation_domain'    => 'VSCmsBundle',
                'config'                => [
                    'toolbar'   => 'full',
                    // Create a toolbar in config for example a 'document_toolbar' and use it 
                    //'toolbar'   => 'document_toolbar',
                    'uiColor'   => '#ffffff',
                ],
            ])
            
            ->add( 'btnApply', SubmitType::class, [
                'label'                 => 'vs_cms.form.apply',
                'translation_domain'    => 'VSCmsBundle',
            ])
            ->add( 'btnSave', SubmitType::class, [
                'label'                 => 'vs_cms.form.save',
                'translation_domain'    => 'VSCmsBundle',
            ])
            ->add( 'btnCancel', ButtonType::class, [
                'label'                 => 'vs_cms.form.cancel',
                'translation_domain'    => 'VSCmsBundle',
            ])
        ;
    }

    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefined([
                'page',
            ])
            ->setAllowedTypes( 'page', PageInterface::class )
        ;
    }
}


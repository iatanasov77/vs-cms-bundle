<?php namespace Vankosoft\CmsBundle\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

use Vankosoft\CmsBundle\Form\Traits\FosCKEditor4Config;

class SliderItemForm extends AbstractForm
{
    use FosCKEditor4Config;
    
    /** @var string */
    protected $sliderClass;
    
    public function __construct(
        string $dataClass,
        string $sliderClass,
        RepositoryInterface $localesRepository,
        RequestStack $requestStack
    ) {
        parent::__construct( $dataClass );
        
        $this->localesRepository    = $localesRepository;
        $this->requestStack         = $requestStack;
        
        $this->sliderClass          = $sliderClass;
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
                'label'                 => 'vs_cms.form.page.published',
                'translation_domain'    => 'VSCmsBundle',
            ])
            
            ->add( 'slider', EntityType::class, [
                'required'              => false,
                'label'                 => 'vs_cms.form.slider_item.slider',
                'translation_domain'    => 'VSCmsBundle',
                'class'                 => $this->sliderClass,
                'choice_label'          => 'name',
                'placeholder'           => 'vs_cms.form.slider_item.slider_placeholder',
                'data'                  => $options['slider'],
            ])
            
            ->add( 'title', TextType::class, [
                'label'                 => 'vs_cms.form.slider_item.title',
                'translation_domain'    => 'VSCmsBundle',
            ])
            
            ->add( 'description', CKEditorType::class, [
                'label'                 => 'vs_cms.form.slider_item.description',
                'translation_domain'    => 'VSCmsBundle',
                'config'                => $this->ckEditorConfig( $options ),
            ])
            
            ->add( 'url', TextType::class, [
                'required'              => false,
                'label'                 => 'vs_cms.form.url',
                'translation_domain'    => 'VSCmsBundle',
                
            ])
            
            ->add( 'photo', FileType::class, [
                'mapped'                => false,
                //'required'              => is_null( $entity->getId() ),
                'required'              => false,
                
                'label'                 => 'vs_cms.form.slider_item.photo',
                'translation_domain'    => 'VSCmsBundle',
                
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Photo',
                    ])
                ],
            ])
        ;
        
    }
    
    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefaults([
                'csrf_protection'               => false,
                'slider'                        => null,
            ])
        ;
            
        $this->onfigureCkEditorOptions( $resolver );
    }
    
    public function getName()
    {
        return 'vs_cms.slider';
    }
}
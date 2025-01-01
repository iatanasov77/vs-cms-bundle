<?php namespace Vankosoft\CmsBundle\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class BannerForm extends AbstractForm
{
    /** @var string */
    protected $bannerPlaceClass;
    
    public function __construct(
        string $dataClass,
        string $bannerPlaceClass,
        RepositoryInterface $localesRepository,
        RequestStack $requestStack
    ) {
        parent::__construct( $dataClass );
        
        $this->localesRepository    = $localesRepository;
        $this->requestStack         = $requestStack;
        
        $this->bannerPlaceClass     = $bannerPlaceClass;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        parent::buildForm( $builder, $options );
        
        $entity         = $builder->getData();
        $currentLocale  = $entity->getTranslatableLocale() ?: $this->requestStack->getCurrentRequest()->getLocale();
        
        $selectedPlaces = [];
        foreach ( $entity->getPlaces() as $place ) {
            $selectedPlaces[] = $place->getId();
        }
        
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
            
            ->add( 'selectedPlaces', HiddenType::class, ['mapped' => false, 'data' => \json_encode( $selectedPlaces )] )
            ->add( 'places', EntityType::class, [
                'required'              => true,
                'multiple'              => true,
                'label'                 => 'vs_cms.form.banner.place',
                'translation_domain'    => 'VSCmsBundle',
                'class'                 => $this->bannerPlaceClass,
                'choice_label'          => 'name',
                'placeholder'           => 'vs_cms.form.banner.place_placeholder',
                //'data'                  => $options['bannerPlace'],
            ])
            
            ->add( 'title', TextType::class, [
                'label'                 => 'vs_cms.form.banner.title',
                'translation_domain'    => 'VSCmsBundle',
            ])
            
            ->add( 'url', TextType::class, [
                'required'              => true,
                'label'                 => 'vs_cms.form.url',
                'translation_domain'    => 'VSCmsBundle',
                
            ])
            
            ->add( 'image', FileType::class, [
                'mapped'                => false,
                'required'              => is_null( $entity->getId() ),
                
                'label'                 => 'vs_cms.form.banner.image',
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
                        'mimeTypesMessage' => 'Please upload a valid Image',
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
                'csrf_protection'   => false,
                'bannerPlace'       => null,
            ])
        ;
    }
    
    public function getName()
    {
        return 'vs_cms.slider';
    }
}
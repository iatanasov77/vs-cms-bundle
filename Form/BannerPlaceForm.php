<?php namespace Vankosoft\CmsBundle\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Liip\ImagineBundle\Imagine\Filter\FilterManager as ImagineFilterManager;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BannerPlaceForm extends AbstractForm
{
    /** @var ImagineFilterManager */
    protected $imagineFilterManager;
    
    public function __construct(
        string $dataClass,
        RepositoryInterface $localesRepository,
        RequestStack $requestStack,
        ImagineFilterManager $imagineFilterManager
    ) {
        parent::__construct( $dataClass );
        
        $this->localesRepository    = $localesRepository;
        $this->requestStack         = $requestStack;
        $this->imagineFilterManager = $imagineFilterManager;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        parent::buildForm( $builder, $options );
        
        $entity         = $builder->getData();
        $currentLocale  = $this->requestStack->getCurrentRequest()->getLocale();
        $filters        = \array_keys( $this->imagineFilterManager->getFilterConfiguration()->all() );
        
        $builder
            ->add( 'currentLocale', ChoiceType::class, [
                'label'                 => 'vs_cms.form.locale',
                'translation_domain'    => 'VSCmsBundle',
                'choices'               => \array_flip( $this->fillLocaleChoices() ),
                'data'                  => $currentLocale,
                'mapped'                => false,
            ])
            
            ->add( 'name', TextType::class, [
                'label'                 => 'vs_cms.form.title',
                'translation_domain'    => 'VSCmsBundle',
                'mapped'                => false,
            ])
            
            ->add( 'imagineFilter', ChoiceType::class, [
                'label'                 => 'vs_cms.form.banner.imagine_filter',
                'translation_domain'    => 'VSCmsBundle',
                'choices'               => \array_combine( $filters, $filters ),
                'placeholder'           => 'vs_cms.form.banner.imagine_filter_placeholder',
            ])
        ;
        
    }
    
    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefaults([
                'csrf_protection'   => false,
            ])
        ;
    }
    
    public function getName()
    {
        return 'vs_cms.banner_place';
    }
}
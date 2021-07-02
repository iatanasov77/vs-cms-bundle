<?php namespace VS\CmsBundle\Form;

use VS\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Doctrine\ORM\EntityRepository;
use VS\CmsBundle\Model\TocPage;
use VS\CmsBundle\Model\TocPageInterface;

class MultiPageTocForm extends AbstractForm
{
    protected $requestStack;
    
    protected $tocPageClass;
    
    public function __construct( RequestStack $requestStack, string $dataClass, string $tocPageClass )
    {
        parent::__construct( $dataClass );
        
        $this->requestStack = $requestStack;
        $this->tocPageClass = $tocPageClass;
    }

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        parent::buildForm( $builder, $options );
        
        $entity         = $builder->getData();
        $currentLocale  = $entity->getTranslatableLocale() ?: $this->requestStack->getCurrentRequest()->getLocale();
        
        $builder
        /*
            ->add( 'locale', ChoiceType::class, [
                'label'                 => 'vs_cms.form.locale',
                'translation_domain'    => 'VSCmsBundle',
                'choices'               => \array_flip( \VS\ApplicationBundle\Component\I18N::LanguagesAvailable() ),
                'data'                  => $currentLocale,
                'mapped'                => false,
            ])
       */     
            ->add( 'tocTitle', TextType::class, [
                'label'                 => 'vs_cms.form.title',
                'translation_domain'    => 'VSCmsBundle',
                'required'              => true
            ])
            
            ->add( 'tocRootPage', EntityType::class, [
                'label'                 => 'vs_cms.form.title',
                'translation_domain'    => 'VSCmsBundle',
                'class'                 => $this->tocPageClass,
                'query_builder' => function ( EntityRepository $er ) {
                    return $er->createQueryBuilder( 'p' )->where( 'p.parent = NULL' );
                },
                'placeholder'           => 'vs_cms.form.title',
                'choice_label'          => 'title',
                'required'              => true
            ])
        ;
    }

    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefined([
                'tocRootPage',
            ])
            ->setAllowedTypes( 'tocRootPage', TocPageInterface::class )
        ;
    }
    
    public function getName()
    {
        return 'vs_cms.multipage_toc';
    }
}


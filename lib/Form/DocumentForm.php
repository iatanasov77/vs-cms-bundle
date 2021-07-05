<?php namespace VS\CmsBundle\Form;

use VS\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DocumentForm extends AbstractForm
{
    protected $requestStack;
    
    protected $multipageTocClass;
    
    public function __construct(
        RequestStack $requestStack,
        string $dataClass,
        string $multipageTocClass
    ) {
            parent::__construct( $dataClass );
            
            $this->requestStack         = $requestStack;
            $this->multipageTocClass    = $multipageTocClass;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        parent::buildForm( $builder, $options );
        
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
            
            ->add( 'title', TextType::class, [
                'label'                 => 'vs_cms.form.title',
                'translation_domain'    => 'VSCmsBundle',
            ])
            
            ->add( 'multipageToc', EntityType::class, [
                'label'                 => 'vs_cms.form.document.document_toc',
                'translation_domain'    => 'VSCmsBundle',
                'class'                 => $this->multipageTocClass,
                'placeholder'           => 'vs_cms.form.document.document_toc',
                'choice_label'          => 'tocTitle',
                'required'              => true
            ])
        ;
    }
    
    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
//         $resolver
//             ->setDefined([
//                 'page',
//             ])
//             ->setAllowedTypes( 'page', PageInterface::class )
//         ;
    }
    
    public function getName()
    {
        return 'vs_cms.document';
    }
}

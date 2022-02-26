<?php namespace Vankosoft\CmsBundle\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

use Doctrine\ORM\EntityRepository;
use Vankosoft\CmsBundle\Model\TocPage;
use Vankosoft\CmsBundle\Model\TocPageInterface;

class DocumentForm extends AbstractForm
{
    protected $requestStack;
    
    protected $tocPageClass;
    
    protected $pagesClass;
    
    public function __construct(
        RequestStack $requestStack,
        string $dataClass,
        string $documentCategoryClass,
        string $tocPageClass
    ) {
        parent::__construct( $dataClass );
        
        $this->requestStack             = $requestStack;
        $this->documentCategoryClass    = $documentCategoryClass;
        $this->tocPageClass             = $tocPageClass;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        parent::buildForm( $builder, $options );
        
        $entity         = $builder->getData();
        $currentLocale  = $entity->getLocale() ?: $this->requestStack->getCurrentRequest()->getLocale();
        
        $builder
            ->add( 'locale', ChoiceType::class, [
                'label'                 => 'vs_cms.form.locale',
                'translation_domain'    => 'VSCmsBundle',
                'choices'               => \array_flip( \Vankosoft\ApplicationBundle\Component\I18N::LanguagesAvailable() ),
                'data'                  => $currentLocale,
                'mapped'                => false,
            ])
            
            ->add( 'category', EntityType::class, [
                'label'                 => 'vs_cms.form.document.document_category',
                'translation_domain'    => 'VSCmsBundle',
                'class'                 => $this->documentCategoryClass,
                'placeholder'           => 'vs_cms.form.document.document_category',
                'choice_label'          => 'name',
                'required'              => true
            ])
            
            /*
            ->add( 'tocRootPage', EntityType::class, [
                'label'                 => 'vs_cms.form.document.document_toc',
                'translation_domain'    => 'VSCmsBundle',
                'class'                 => $this->tocPageClass,
                'query_builder' => function ( EntityRepository $er ) {
                    return $er->createQueryBuilder( 'p' )->where( 'p.parent IS NULL' );
                },
                'placeholder'           => 'vs_cms.form.document.document_toc',
                'choice_label'          => 'title',
                'required'              => true
            ])
            */
        
            ->add( 'title', TextType::class, [
                'label'                 => 'vs_cms.form.title',
                'translation_domain'    => 'VSCmsBundle',
                'required'              => true
            ])
        
            ->add( 'text', CKEditorType::class, [
                'label'                 => 'vs_cms.form.page.page_content',
                'translation_domain'    => 'VSCmsBundle',
                'config'                => [
                    'toolbar'           => 'full',
                    // Create a toolbar in config for example a 'document_toolbar' and use it
                    //'toolbar'   => 'document_toolbar',
                    'uiColor'   => '#ffffff',
                ],
                'mapped'                => false,
            ])
        ;
    }
    
    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefaults([
                'csrf_protection' => false,
            ])
            ->setDefined([
                'tocRootPage',
            ])
            ->setAllowedTypes( 'tocRootPage', TocPageInterface::class )
        ;
    }
    
    public function getName()
    {
        return 'vs_cms.document';
    }
}

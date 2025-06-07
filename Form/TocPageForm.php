<?php namespace Vankosoft\CmsBundle\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use daddl3\SymfonyCKEditor5WebpackViteBundle\Form\Ckeditor5TextareaType;

use Doctrine\ORM\EntityRepository;
use Vankosoft\CmsBundle\Form\Traits\FosCKEditor4Config;

class TocPageForm extends AbstractForm
{
    use FosCKEditor4Config;
    
    /**
     * Which CkEditor Version to Use
     * ------------------------
     * CkEditor 4 provided by FOSCKEditorBundle OR
     * CkEditor 5 provided by
     *
     * @var string
     */
    protected $useCkEditor;
    
    /** @var string */
    protected $ckeditor5Editor;
    
    public function __construct(
        string $dataClass,
        RepositoryInterface $localesRepository,
        RequestStack $requestStack,
        string $useCkEditor,
        string $ckeditor5Editor
    ) {
        parent::__construct( $dataClass );
        
        $this->localesRepository    = $localesRepository;
        $this->requestStack         = $requestStack;
        
        $this->useCkEditor          = $useCkEditor;
        $this->ckeditor5Editor      = $ckeditor5Editor;
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
                //'mapped'                => false,
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
        ;
            
        if ( $this->useCkEditor == '5' ) {
            $builder->add( 'text', Ckeditor5TextareaType::class, [
                'label'                 => 'vs_cms.form.page.page_content',
                'translation_domain'    => 'VSCmsBundle',
                'required'              => false,
                
                'attr' => [
                    'data-ckeditor5-config' => $this->ckeditor5Editor
                ],
            ]);
        } else {
            $builder->add( 'text', CKEditorType::class, [
                'label'                 => 'vs_cms.form.page.page_content',
                'translation_domain'    => 'VSCmsBundle',
                'required'              => false,
                'config'                => $this->ckEditorConfig( $options ),
            ]);
        }
    }

    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefaults([
                'validation_groups' => false,
                'csrf_protection'   => false,
                'tocRootPage'       => null,
            ])
            
            ->setDefined([
                'page',
            ])
        ;
            
        $this->configureCkEditorOptions( $resolver );
    }
    
    public function getName()
    {
        return 'vs_cms.toc_page';
    }
}

<?php namespace Vankosoft\CmsBundle\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use daddl3\SymfonyCKEditor5WebpackViteBundle\Form\Ckeditor5TextareaType;

use Vankosoft\CmsBundle\Model\Page;
use Vankosoft\CmsBundle\Model\Interfaces\PageInterface;
use Vankosoft\CmsBundle\Model\Interfaces\PageCategoryInterface;
use Vankosoft\CmsBundle\Form\Traits\FosCKEditor4Config;

class PageForm extends AbstractForm
{
    use FosCKEditor4Config;
    
    /** @var string */
    protected $categoryClass;
    
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
        string $categoryClass,
        string $useCkEditor,
        string $ckeditor5Editor
    ) {
        parent::__construct( $dataClass );
        
        $this->localesRepository    = $localesRepository;
        $this->requestStack         = $requestStack;
        
        $this->categoryClass        = $categoryClass;
        
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
            
            ->add( 'enabled', CheckboxType::class, [
                'label'                 => 'vs_cms.form.page.published',
                'translation_domain'    => 'VSCmsBundle',
            ])
            
            ->add( 'category_taxon', EntityType::class, [
                'label'                 => 'vs_cms.form.page.categories',
                'translation_domain'    => 'VSCmsBundle',
                'multiple'              => true,
                'required'              => false,
                'mapped'                => false,
                'placeholder'           => 'vs_cms.form.page.categories_placeholder',
                
                'class'                 => $this->categoryClass,
                'data'                  => $entity->getCategories(),
                
                'choice_label'          => function ( PageCategoryInterface $category ) use ( $currentLocale ) {
                    return $category->getNameTranslated( $currentLocale );
                },
                'choice_value'          => function ( PageCategoryInterface $category ) {
                    //return $category ? $category->getTaxon()->getId() : 0;
                    return $category ? $category->getId() : 0;
                },
            ])
            
            ->add( 'description', TextType::class, [
                'label'                 => 'vs_cms.form.description',
                'translation_domain'    => 'VSCmsBundle',
                'required'              => false,
            ])
            ->add( 'title', TextType::class, [
                'label'                 => 'vs_cms.form.title',
                'translation_domain'    => 'VSCmsBundle',
            ])
            ->add( 'tagsInputWhitelist', HiddenType::class, ['mapped' => false, 'required' => false] )
            ->add( 'tags', TextType::class, [
                'label'                 => 'vs_application.form.tags',
                'translation_domain'    => 'VSApplicationBundle',
                'required'              => false,
            ])
            ->add( 'slug', TextType::class, [
                'label'                 => 'vs_cms.form.page.slug',
                'translation_domain'    => 'VSCmsBundle',
            ])
        ;
            
        if ( $this->useCkEditor == '5' ) {
            $builder->add( 'text', Ckeditor5TextareaType::class, [
                'label'                 => 'vs_cms.form.page.page_content',
                'translation_domain'    => 'VSCmsBundle',
                
                'attr' => [
                    'data-ckeditor5-config' => $this->ckeditor5Editor
                ],
            ]);
        } else {
            $builder->add( 'text', CKEditorType::class, [
                'label'                 => 'vs_cms.form.page.page_content',
                'translation_domain'    => 'VSCmsBundle',
                'config'                => $this->ckEditorConfig( $options ),
            ]);
        }
    }

    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefaults([
                'csrf_protection'   => false,
            ])
            
            ->setDefined([
                'page',
            ])
            
            ->setAllowedTypes( 'page', PageInterface::class )
        ;
            
        $this->onfigureCkEditorOptions( $resolver );
    }
    
    public function getName()
    {
        return 'vs_cms.page';
    }
}


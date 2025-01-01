<?php namespace Vankosoft\CmsBundle\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Component\Resource\Repository\RepositoryInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PageCategoryForm extends AbstractForm
{
    protected $categoryClass;
    
    protected $repository;
    
    protected $requestStack;
    
    public function __construct(
        string $dataClass,
        RepositoryInterface $localesRepository,
        RequestStack $requestStack,
        RepositoryInterface $repository
    ) {
        parent::__construct( $dataClass );
        
        $this->localesRepository    = $localesRepository;
        $this->requestStack         = $requestStack;
        
        $this->categoryClass        = $dataClass;
        $this->repository           = $repository;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        parent::buildForm( $builder, $options );
        
        $category       = $options['data'];
        $currentLocale  = $this->requestStack->getCurrentRequest()->getLocale();
        
        $builder
            ->setMethod( $category && $category->getId() ? 'PUT' : 'POST' )
            
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
            ] )
            
            ->add( 'parent', EntityType::class, [
                'label'                 => 'vs_cms.form.category.parent_category',
                'translation_domain'    => 'VSCmsBundle',
                'class'                 => $this->categoryClass,
                'query_builder'         => function ( RepositoryInterface $er ) use ( $category )
                {
                    $qb = $er->createQueryBuilder( 'pc' );
                    if  ( $category && $category->getId() ) {
                        $qb->where( 'pc.id != :id' )->setParameter( 'id', $category->getId() );
                    }
                    
                    return $qb; 
                },
                'choice_label'  => 'name',
                
                'required'      => false,
                'placeholder'   => 'vs_cms.form.category.parent_category_placeholder',
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
        ;
    }
    
    public function getName()
    {
        return 'vs_cms.page_category';
    }
}

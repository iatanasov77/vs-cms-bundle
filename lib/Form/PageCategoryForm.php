<?php namespace VS\CmsBundle\Form;

use VS\ApplicationBundle\Form\AbstractForm;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use VS\ApplicationBundle\Component\I18N;

class PageCategoryForm extends AbstractForm
{
    protected $categoryClass;
    
    protected $repository;
    
    public function __construct( string $dataClass, EntityRepository $repository )
    {
        parent::__construct( $dataClass );
        
        $this->categoryClass    = $dataClass;
        $this->repository       = $repository;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        parent::buildForm( $builder, $options );
        
        $category   = $options['data'];
        
        $builder
            ->setMethod( $category && $category->getId() ? 'PUT' : 'POST' )
            
            ->add( 'currentLocale', ChoiceType::class, [
                'label'                 => 'vs_cms.form.locale',
                'translation_domain'    => 'VSCmsBundle',
                'choices'               => \array_flip( I18N::LanguagesAvailable() ),
                'mapped'                => false,
            ])
        
            ->add( 'name', TextType::class, [
                'label'                 => 'vs_cms.form.title',
                'translation_domain'    => 'VSCmsBundle',
            ] )
            
            ->add( 'parent', EntityType::class, [
                'label'                 => 'vs_cms.form.category.parent_category',
                'translation_domain'    => 'VSCmsBundle',
                'class'                 => $this->categoryClass,
                'query_builder'         => function ( EntityRepository $er ) use ( $category )
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
    
    public function getName()
    {
        return 'vs_cms.page_category';
    }
}

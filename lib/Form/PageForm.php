<?php namespace VS\CmsBundle\Form;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

use VS\CmsBundle\Model\PageInterface;

class PageForm extends AbstractResourceType
{
    protected $categoryClass;
    
    public function __construct( string $dataClass, string $categoryClass )
    {
        parent::__construct( $dataClass );
        
        $this->categoryClass = $categoryClass;
    }

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'locale', ChoiceType::class, [
                'label'     => 'Locale',
                'choices'   => \array_flip( \VS\ApplicationBundle\Component\I18N::LanguagesAvailable() ),
                'mapped'    => false,
            ])
            
            ->add( 'enabled', CheckboxType::class, ['label' => 'Enabled'] )

            ->add( 'category_taxon', ChoiceType::class, [
                'label'     => 'Category',
                'multiple'  => true,
                'required'  => false,
                'mapped'    => false,
            ])
            
            ->add( 'title', TextType::class, ['label' => 'Title'] )
            ->add( 'slug', TextType::class, ['label' => 'Slug'] )
            
            ->add( 'text', CKEditorType::class, [
                'label'     => 'Page Content',
                'config'    => ['uiColor' => '#ffffff'],
            ])
            
            ->add( 'btnApply', SubmitType::class, ['label' => 'Apply'] )
            ->add( 'btnSave', SubmitType::class, ['label' => 'Save'] )
            ->add( 'btnCancel', ButtonType::class, ['label' => 'Cancel'] )
        ;
    }

    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefined([
                'page',
            ])
            ->setAllowedTypes( 'page', PageInterface::class )
        ;
    }
}


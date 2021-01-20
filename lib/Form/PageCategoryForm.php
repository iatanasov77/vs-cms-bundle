<?php namespace VS\CmsBundle\Form;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use VS\CmsBundle\Entity\PageCategory;

class PageCategoryForm extends AbstractResourceType
{
    protected $categoryClass;
    
    public function __construct( string $dataClass )
    {
        parent::__construct( $dataClass );
        
        $this->categoryClass = $dataClass;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'locale', ChoiceType::class, [
                'label'     => 'Locale',
                'choices'  => \array_flip( \VS\ApplicationBundle\Component\I18N::LanguagesAvailable() ),
                'mapped'    => false,
            ])
        
            ->add( 'name', TextType::class, ['label' => 'Title'] )
            
            ->add( 'parent', EntityType::class, [
                'label'         => 'Parent Category',
                'class'         => $this->categoryClass,
                'placeholder'   => '-- Set As Root --',
                'choice_label'  => 'name',
                'required'      => false
            ])

            ->add( 'btnSave', SubmitType::class, ['label' => 'Save'] )
            ->add( 'btnCancel', ButtonType::class, ['label' => 'Cancel'] )
        ;
    }
}

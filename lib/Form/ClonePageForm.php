<?php namespace VS\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

use App\Entity\Cms\PageCategory;

class ClonePageForm extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'category', EntityType::class, [
                'label' => 'Category',
                'class' => PageCategory::class,
                'choice_label' => 'name',
                'placeholder' => '--- Choose a Category ---',
                'required' => true
            ])
            ->add( 'newTitle', TextType::class, ['label' => 'New Title'] )
            
            ->add( 'btnSave', SubmitType::class, ['label' => 'Save'] )
            ->add( 'btnCancel', ButtonType::class, ['label' => 'Cancel'] )
        ;
    }
}

<?php namespace Vankosoft\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

use App\Entity\Cms\PageCategory;

class ClonePageForm extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        $builder
            ->add( 'category', EntityType::class, [
                'label'                 => 'vs_cms.form.page.category',
                'translation_domain'    => 'VSCmsBundle',
                'class'                 => PageCategory::class,
                'choice_label'          => 'name',
                'placeholder'           => 'vs_cms.form.page.category_placeholder',
                'required'              => true
            ])
            ->add( 'newTitle', TextType::class, [
                'label'                 => 'vs_cms.form.clone_page.new_title',
                'translation_domain'    => 'VSCmsBundle',
            ])
            
            ->add( 'btnSave', SubmitType::class, ['label' => 'vs_cms.form.save', 'translation_domain' => 'VSCmsBundle',] )
            ->add( 'btnCancel', ButtonType::class, ['label' => 'vs_cms.form.cancel', 'translation_domain' => 'VSCmsBundle',] )
        ;
    }
}

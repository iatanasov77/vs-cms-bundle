<?php namespace Vankosoft\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class PreviewPageForm extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        $builder
            ->add( 'pagePreviewUrl', HiddenType::class )
            ->add( 'layout', ChoiceType::class, [
                'placeholder'   => '-- Choose a Layout --',
                'required'      => true,
                'choices'  => [
                    'admin/layout.html.twig'        => 'admin/layout.html.twig',
                    'blog/layout.html.twig'         => 'blog/layout.html.twig',
                    'website/layout.html.twig'      => 'website/layout.html.twig',
                    'website/layout-docs.html.twig' => 'website/layout-docs.html.twig',
                ],
            ])
            
            ->add( 'btnSave', SubmitType::class, ['label' => 'vs_cms.form.save', 'translation_domain' => 'VSCmsBundle',] )
            ->add( 'btnCancel', ButtonType::class, ['label' => 'vs_cms.form.cancel', 'translation_domain' => 'VSCmsBundle',] )
        ;
    }
}

<?php namespace Vankosoft\CmsBundle\Form\FileManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class UploadFileForm extends AbstractType
{    
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        $builder
            ->add( 'directory', TextType::class, [
                'label'                 => 'vs_cms.form.filemanager.file_directory_lable',
                'translation_domain'    => 'VSCmsBundle',
                'mapped'                => false,
            ])
            ->add( 'file', FileType::class, [
                'label'                 => 'vs_cms.form.filemanager.file_lable',
                'translation_domain'    => 'VSCmsBundle',
                'mapped'                => false,
                
                // make it optional so you don't have to re-upload the Profile Image
                // every time you edit the Profile details
                'required'              => true,
                
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints'           => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'vs_cms.form.filemanager.file_info',
                    ])
                ],
            ])
            
            ->add( 'btnApply', SubmitType::class, ['label' => 'vs_application.form.apply', 'translation_domain' => 'VSApplicationBundle',] )
            ->add( 'btnSave', SubmitType::class, ['label' => 'vs_application.form.save', 'translation_domain' => 'VSApplicationBundle',] )
            ->add( 'btnCancel', ButtonType::class, ['label' => 'vs_application.form.cancel', 'translation_domain' => 'VSApplicationBundle',] )
        ;
    }
}

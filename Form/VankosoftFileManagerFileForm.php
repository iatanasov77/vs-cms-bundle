<?php namespace Vankosoft\CmsBundle\Form;

use Vankosoft\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

use Vankosoft\CmsBundle\Model\FileManagerFileInterface;

class VankosoftFileManagerFileForm extends AbstractForm
{
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        parent::buildForm( $builder, $options );
        
        $builder
            
//             ->add( 'title', TextType::class, [
//                 'label'                 => 'vs_cms.form.title',
//                 'translation_domain'    => 'VSCmsBundle',
//             ])

            ->add( 'fileManagerId', HiddenType::class, ['mapped' => false] )
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
            
        ;
    }

    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
            ->setDefaults([
                'csrf_protection' => false,
            ])
            ->setDefined([
                'file_manager_file',
            ])
            ->setAllowedTypes( 'file_manager_file', FileManagerFileInterface::class )
        ;
    }
    
    public function getName()
    {
        return 'vs_cms.filemanager_file';
    }
}


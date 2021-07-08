<?php namespace VS\CmsBundle\Form\FileManager;

use VS\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class UploadFileForm extends AbstractForm
{
    public function __construct( string $dataClass )
    {
        parent::__construct( $dataClass );
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        parent::buildForm( $builder, $options );
        
        $builder
            ->add( 'file', FileType::class, [
                'label' => 'vs_users.form.filemanager.file_lable',
                'mapped' => false,
                
                // make it optional so you don't have to re-upload the Profile Image
                // every time you edit the Profile details
                'required' => true,
                
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
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
    
    public function configureOptions( OptionsResolver $resolver ) : void
    {
        parent::configureOptions( $resolver );
        
//         $resolver
//             ->setDefined([
//                 'users',
//             ])
//             ->setAllowedTypes( 'users', UserInterface::class )
//         ;
    }
    
    public function getName()
    {
        return 'vs_cms.filemanager.upload_file';
    }
}

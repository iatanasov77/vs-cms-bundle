<?php namespace VS\CmsBundle\Form;

use VS\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use VS\CmsBundle\Model\FileManagerFileInterface;

class VankosoftFileManagerFileForm extends AbstractForm
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        parent::buildForm( $builder, $options );
        
//         $builder
            
//             ->add( 'title', TextType::class, [
//                 'label'                 => 'vs_cms.form.title',
//                 'translation_domain'    => 'VSCmsBundle',
//             ])
            
//         ;
    }

    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver
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


<?php namespace VS\CmsBundle\Form;

use VS\ApplicationBundle\Form\AbstractForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Doctrine\ORM\EntityRepository;

class TocPageForm extends AbstractForm
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        parent::buildForm( $builder, $options );
        
        $builder
        /*
            ->add( 'locale', ChoiceType::class, [
                'label'                 => 'vs_cms.form.locale',
                'translation_domain'    => 'VSCmsBundle',
                'choices'               => \array_flip( \VS\ApplicationBundle\Component\I18N::LanguagesAvailable() ),
                'data'                  => $currentLocale,
                'mapped'                => false,
            ])
       */
        
        ->add( 'parent', EntityType::class, [
            'mapped'                => false,
            'required'              => true,
            'label'                 => 'vs_cms.form.parent',
            'translation_domain'    => 'VSCmsBundle',
            'class'                 => $this->dataClass,
            'choice_label'          => 'title',
            'query_builder'         => function ( EntityRepository $er ) use ( $options )
            {
                //var_dump( $er ); die;
                return $er->createQueryBuilder( 't' )
                            ->where( 't.root = :root' )
                            ->setParameter( 'rootTaxon', $options['root'] );
            }
        ])
        
        ->add( 'name', TextType::class, ['label' => 'vs_application.form.name', 'translation_domain' => 'VSApplicationBundle',] )
        ;
    }

    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver->setDefaults([
            'root'  => null,
        ]);
    }
    
    public function getName()
    {
        return 'vs_cms.toc_page';
    }
}


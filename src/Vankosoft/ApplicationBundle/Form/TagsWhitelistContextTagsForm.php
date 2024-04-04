<?php namespace Vankosoft\ApplicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Vankosoft\ApplicationBundle\Form\Type\WhitelistContextTagType;
use Vankosoft\ApplicationBundle\Model\Interfaces\TagsWhitelistContextInterface;

class TagsWhitelistContextTagsForm extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        $builder
            ->add( 'tags', CollectionType::class, [
                'entry_type'   => WhitelistContextTagType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'by_reference' => false
            ])
        ;
    }
    
    public function configureOptions( OptionsResolver $resolver ): void
    {
        parent::configureOptions( $resolver );
        
        $resolver->setDefaults([
            'csrf_protection'   => false,
            'data_class'        => TagsWhitelistContextInterface::class
        ]);
    }
    
    public function getName()
    {
        return 'vs_application.tags_whitelist_context_tags';
    }
}
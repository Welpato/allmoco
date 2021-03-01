<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SurveyType
 */
class SurveyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class)
            ->add(
                'date',
                DateType::class,
                [
                    'widget' => 'choice',
                    'years' => range(date('Y'), date('Y')),
                    'months' => range(date('m'), date('m')),
                    'days' => range(date('d'), date('d')),
                ]
            );

        $saveLabel = 'Start new Survey';
        if(!$options['isNew']){
            $builder->add(
                'active',
                CheckboxType::class,
                [
                    'label' => 'Is Active',
                    'required' => false,
                ]
            );
            $saveLabel = 'Update survey';
        }

        $builder->add('start',
                SubmitType::class,
                [
                    'label' => $saveLabel,
                ],
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'isNew' => true,
            ]
        );
        $resolver->setAllowedTypes('isNew', 'bool');
    }

}

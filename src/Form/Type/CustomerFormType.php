<?php

namespace App\Form\Type;

use App\Entity\Customers;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customers::class,
            'csrf_protection' => false
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname', TextType::class)
            ->add('sex', ChoiceType::class, [
                'choices' => [
                    'Male' => true,
                    'Female' => false
                ]
            ])
            ->add('telephone', TextType::class)
            ->add('address', TextType::class)
            ->add('birthday', DateType::class, ['widget' => 'single_text']);
    }
}

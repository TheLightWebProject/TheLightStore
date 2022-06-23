<?php

namespace App\Form\Type;

use App\Entity\Brands;
use App\Entity\Products;
use App\Entity\Suppliers;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
            'csrf_protection' => false
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', TextType::class)
            ->add('smallDesc', TextType::class, [
                'label' => 'Small description'
            ])
            ->add('detailDesc', TextareaType::class, [
                'label' => 'Detail description'
            ])
            ->add('quantity', IntegerType::class)
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false
            ])
            ->add('brand', EntityType::class, [
                'class' => Brands::class,
                'choice_label' => 'name'
            ])
            ->add('supplier', EntityType::class, [
                'class' => Suppliers::class,
                'choice_label' => 'name'
            ])
            ->add('save', SubmitType::class, [
                'label' => "Save"
            ]);
    }
}

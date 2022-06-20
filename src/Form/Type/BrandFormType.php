<?php
namespace App\Form\Type;

use App\Entity\Brands;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BrandFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Brands::class
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class)
        ->add('decrip', TextType::class)
        ->add('image', FileType::class, [
            'label' => 'Image',
            'mapped' => false,
            'required' => false
        ])
        ->add('save', SubmitType::class, [
            'label' => "Save"
        ]);
    }
}

?>
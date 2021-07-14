<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\UsedProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UsedProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
        'class' => Product::class,
        'choice_label' => 'name',
                'choice_label' => 'bar_code',
        ])
            ->add('expiration')
//            ->add('owner')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UsedProduct::class,
        ]);
    }
}

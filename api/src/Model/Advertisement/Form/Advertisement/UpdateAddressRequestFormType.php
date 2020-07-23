<?php

declare (strict_types = 1);

namespace App\Model\Advertisement\Form\Advertisement;

use App\Model\Advertisement\Command\Advertisement\CreateAddressRequestCommand;
use App\Model\Advertisement\Command\Advertisement\UpdateAddressRequestCommand;
use App\Model\Advertisement\Entity\Address;
use App\Model\Advertisement\Entity\BodyAdvertisement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class UpdateAddressRequestFormType
 * @package App\Model\Advertisement\Form\Advertisement
 */
class UpdateAddressRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
		    ->add('id', Type\IntegerType::class)
		    ->add('country', Type\TextType::class)
		    ->add('city', Type\TextType::class)
		    ->add('district', Type\TextType::class)
		    ->add('street', Type\TextType::class)
		    ->add('house', Type\TextType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UpdateAddressRequestCommand::class,
	        'csrf_protection' => false
        ]);
    }
}

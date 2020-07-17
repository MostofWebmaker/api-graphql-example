<?php

declare (strict_types = 1);

namespace App\Model\Advertisement\Form\Advertisement;

use App\Model\Advertisement\Command\Advertisement\CreateAddressRequestCommand;
use App\Model\Advertisement\Entity\Address;
use App\Model\Advertisement\Entity\BodyAdvertisement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class AddAddressRequestFormType
 * @package App\Model\User\Form\Advertisement
 */
class CreateAddressRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
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
            //'data_class' => Address::class,
            'data_class' => CreateAddressRequestCommand::class,
	        // enable/disable CSRF protection for this form
	        'csrf_protection' => false
        ]);
    }
}

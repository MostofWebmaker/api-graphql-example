<?php

declare (strict_types = 1);

namespace App\Model\Advertisement\Form\Advertisement;

use App\Model\Advertisement\Command\Advertisement\CreateBodyAdvertisementRequestCommand;
use App\Model\Advertisement\Entity\BodyAdvertisement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;


/**
 * Class CreateBodyAdvertisementRequestFormType
 * @package App\Model\Advertisement\Form\Advertisement
 */
class CreateBodyAdvertisementRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
		    ->add('title',Type\TextType::class)
		    ->add('price', Type\IntegerType::class)
		    ->add('description', Type\TextType::class);
//		    ->add('imageUrl');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => BodyAdvertisement::class,
            'data_class' => CreateBodyAdvertisementRequestCommand::class,
	        // enable/disable CSRF protection for this form
//	        'csrf_protection' => false
        ]);
    }
}

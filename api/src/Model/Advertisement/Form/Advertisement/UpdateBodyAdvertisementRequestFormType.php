<?php

declare (strict_types = 1);

namespace App\Model\Advertisement\Form\Advertisement;

use App\Model\Advertisement\Command\Advertisement\CreateBodyAdvertisementRequestCommand;
use App\Model\Advertisement\Command\Advertisement\UpdateAdvertisementRequestCommand;
use App\Model\Advertisement\Command\Advertisement\UpdateBodyAdvertisementRequestCommand;
use App\Model\Advertisement\Entity\BodyAdvertisement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;


/**
 * Class UpdateBodyAdvertisementRequestFormType
 * @package App\Model\Advertisement\Form\Advertisement
 */
class UpdateBodyAdvertisementRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
		    ->add('id',Type\IntegerType::class)
		    ->add('title',Type\TextType::class)
		    ->add('price', Type\IntegerType::class)
		    ->add('description', Type\TextType::class);
//            ->add('photos', CollectionType::class, [
//                'entry_type' => Type\TextType::class,
//                'allow_add' => true,
//                'entry_options' => ['label' => false],
//                'by_reference' => false,
//                'required' => false
//            ]);

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => BodyAdvertisement::class,
            'data_class' => UpdateBodyAdvertisementRequestCommand::class,
	        // enable/disable CSRF protection for this form
//	        'csrf_protection' => false
        ]);
    }
}

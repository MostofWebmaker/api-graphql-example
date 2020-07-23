<?php

declare (strict_types = 1);

namespace App\Model\Advertisement\Form\Advertisement;

use App\Model\Advertisement\Command\Advertisement\UpdateAdvertisementRequestCommand;
use App\Model\Advertisement\Entity\Address;
use App\Model\Advertisement\Entity\Advertisement;
use App\Model\Advertisement\Entity\BodyAdvertisement;
use App\Model\Advertisement\Form\Advertisement\CreateBodyAdvertisementRequestFormType;
use App\Model\Advertisement\Form\Advertisement\CreateAddressRequestFormType;
use App\Model\Advertisement\Command\Advertisement\CreateAdvertisementRequestCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class UpdateAdvertisementRequestFormType
 * @package App\Model\Advertisement\Form\Advertisement
 */
class UpdateAdvertisementRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
		    ->add('id', Type\IntegerType::class)
		    ->add('categoryAdvertisementId', Type\IntegerType::class)
            ->add('bodyAdvertisement', UpdateBodyAdvertisementRequestFormType::class)
            ->add('address', UpdateAddressRequestFormType::class)
		    ->add('subwayStation', Type\TextType::class)
            ->add('photos', CollectionType::class, [
                'entry_type' => UpdatePhotosRequestFormType::class,
                'allow_add' => true,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'required' => false
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UpdateAdvertisementRequestCommand::class,
	        'csrf_protection' => false
        ]);
    }
}

<?php

declare (strict_types = 1);

namespace App\Model\Advertisement\Form\Advertisement;

use App\Model\Advertisement\Command\Advertisement\AdvertisementStatusChangeRequestCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class AdvertisementStatusChangeRequestFormType
 * @package App\Model\Advertisement\Form\Advertisement
 */
class AdvertisementStatusChangeRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
		    ->add('advertisementId', Type\IntegerType::class)
		    ->add('statusId', Type\IntegerType::class)
		    ->add('userId', Type\IntegerType::class)
		    ->add('message', Type\TextType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AdvertisementStatusChangeRequestCommand::class,
	        'csrf_protection' => false
        ]);
    }
}

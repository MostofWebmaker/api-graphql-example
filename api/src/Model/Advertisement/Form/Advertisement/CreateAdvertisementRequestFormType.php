<?php

declare (strict_types = 1);

namespace App\Model\Advertisement\Form\Advertisement;

use App\Model\Advertisement\Command\Advertisement\CreateAdvertisementRequestCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class AddAdvertisementRequestFormType
 * @package App\Model\User\Form\Advertisement
 */
class CreateAdvertisementRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
		    ->add('categoryAdvertisementId', Type\IntegerType::class, [
//            'required' => false
            ])
//            ->add('cover', FileType::class, [ 'data_class' => null ])
//		    ->add('bodyAdvertisement', CollectionType::class, [
//                'entry_type' => CreateBodyAdvertisementRequestFormType::class,
//                'allow_add' => true,
//               'entry_options' => ['label' => false],
//                'by_reference' => false,
//               'required' => false
//            ])
            ->add('bodyAdvertisement', CreateBodyAdvertisementRequestFormType::class)
//		    ->add('address', CollectionType::class, [
//                'entry_type' => CreateAddressRequestFormType::class,
//                'allow_add' => true,
//                'entry_options' => ['label' => false],
//                'by_reference' => false,
//                'required' => false
//            ])
            ->add('address', CreateAddressRequestFormType::class)
		    ->add('subwayStation', Type\TextType::class, [
//                'required' => false
            ])
//            ->add('photos', Type\TextType::class);
            ->add('photos', CollectionType::class, [
                    'entry_type' => Type\TextType::class,
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
            'data_class' => CreateAdvertisementRequestCommand::class,
            //'data_class' => null,
	        // enable/disable CSRF protection for this form
	        'csrf_protection' => false
        ]);
    }
}

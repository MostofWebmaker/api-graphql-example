<?php

namespace App\ReadModel\Advertisement\Filter;

use App\Model\Advertisement\Entity\AdvertisementStatusType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertisementForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Заголовок',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('userFio', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Пользователь',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('status', Type\ChoiceType::class, ['choices' => [
                AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_BANNED] => AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_BANNED],
                AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_DRAFT] =>  AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_DRAFT],
                AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_ON_MODERATION] => AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_ON_MODERATION],
                AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_ACTIVE] => AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_ACTIVE],
                AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_IN_ARCHIVE] => AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_IN_ARCHIVE],
                AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_PREMIUM_ACTIVE] => AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_PREMIUM_ACTIVE]
            ], 'required' => false, 'placeholder' => 'Все статусы', 'attr' => ['onchange' => 'this.form.submit()']]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdvertisementFilter::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}

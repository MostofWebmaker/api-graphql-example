<?php

namespace App\ReadModel\Advertisement\ChangeAdvertisementStatus;

use App\Model\Advertisement\Entity\AdvertisementStatusType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeAdvertisementStatusAdminForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statusId', Type\ChoiceType::class, ['choices' => [
                AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_BANNED] => AdvertisementStatusType::STATUS_BANNED,
                AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_DRAFT] => AdvertisementStatusType::STATUS_DRAFT,
                AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_ON_MODERATION] => AdvertisementStatusType::STATUS_ON_MODERATION,
                AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_ACTIVE] => AdvertisementStatusType::STATUS_ACTIVE,
                AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_IN_ARCHIVE] => AdvertisementStatusType::STATUS_IN_ARCHIVE,
                AdvertisementStatusType::ADVERTISEMENT_ALL_STATUS_LIST[AdvertisementStatusType::STATUS_PREMIUM_ACTIVE] => AdvertisementStatusType::STATUS_PREMIUM_ACTIVE
            ], 'required' => false, 'placeholder' => 'Все статусы'])
            ->add('message', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Сообщение пользователю (отсылается только при перевод из статуса "на модерации")'
            ]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangeAdvertisementStatusAdminCommand::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}

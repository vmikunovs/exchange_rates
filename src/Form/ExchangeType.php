<?php

namespace App\Form;

use App\Entity\Currency;
use App\Services\ExchangeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExchangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $model = $options['data'];
        $builder
            ->add(
                'fromCurrency',
                ChoiceType::class,
                array(
                    'choices' => $model->getCurrencies(),
                    'choice_label' => function (Currency $currency) {
                        return sprintf('%s', $currency->getCode());
                    },
                    'preferred_choices' => function (Currency $currency) use ($model) {
                        return $currency->getCode() == $model->getFromCurrency();
                    },
                )
            )
            ->add(
                'toCurrency',
                ChoiceType::class,
                array(
                    'choices' => $model->getCurrencies(),
                    'choice_label' => function (Currency $currency) {
                        return sprintf('%s', $currency->getCode());
                    },
                    'preferred_choices' => function (Currency $currency) use ($model) {
                        return $currency->getCode() == $model->getToCurrency();
                    },
                )
            )
            ->add('fromAmount', NumberType::class)
            ->add('toAmount', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => ExchangeModel::class,
            )
        );
    }
}

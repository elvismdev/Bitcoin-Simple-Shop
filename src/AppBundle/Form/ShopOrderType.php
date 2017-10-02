<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopOrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email')->add('name')->add('lastName')->add('address')->add('address2')->add('city')->add('state')->add('zip')->add('country')->add('product')->add('orderTotalBtc')->add('orderTotalUsd')->add('createdAt')->add('updatedAt')->add('orderPaid')->add('orderStatus')->add('amountPaid')->add('difference')->add('transactionHash')->add('btcAddressId');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ShopOrder'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_shoporder';
    }


}

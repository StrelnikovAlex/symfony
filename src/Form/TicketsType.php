<?php

namespace App\Form;

use App\Entity\Tickets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TicketsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('type', ChoiceType::class, [
              'choices' => [
                  'bug' => ('bug'),
                  'task' => ('task'),
              ]

            ])
            ->add('status', ChoiceType::class, [
              'choices' => [
                  'new' => ('new'),
                  'in progress' => ('in progress'),
                  'testing' => ('testing'),
                  'done' => ('done'),
              ]
            ])
            ->add('description')
            ->add('file')
            ->add('file_name')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tickets::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Tickets;
use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class TicketsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tags_form = [];
        $tags = $builder->getData()->getTags();
        foreach ($tags as $tag) {
            $tags_form[] = $tag->getName();
        }

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
            ->add('tags', TextType::class, ['mapped'=>false, 'data' => implode(', ', $tags_form)])
            ->add('file', FileType::class, [
                'label' => 'Upload file',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tickets::class,
        ]);
    }
}

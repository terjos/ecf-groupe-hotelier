<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\Establishment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// https://symfony.com/doc/current/form/dynamic_form_modification.html
class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('establishment', EntityType::class, [
                'class' => 'App\Entity\Establishment',
                'choice_label' => 'name',
                'label' => 'Choisissez un établissement',
                'placeholder' => 'Choisissez un établissement',
                'mapped' => false,
            ])
            ->add('room', ChoiceType::class, [
                'label' => 'Choisissez une suite',
                'placeholder' => 'Choisissez un établissement',
                'required' => false,
                'attr' => [
                    'disabled' => true,
                ],
            ])
            ->add('startAt', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'format' => "yyyy-MM-dd",
                'html5' => true,
                'required' => true,
                'attr' => [
                    'min' => (new \DateTimeImmutable())->format('Y-m-d'),
                ],
            ])
            ->add('endAt', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'format' => "yyyy-MM-dd",
                'html5' => true,
                'attr' => [
                    'min' => (new \DateTimeImmutable())->format('Y-m-d'),
                ],
            ]);

        $formModifier = function (FormInterface $form, Establishment $establishment = null) {
            $rooms = null === $establishment ? [] : $establishment->getRooms();
            $form->add('room', EntityType::class, [
                'class' => Room::class,
                'choices' => $rooms,
                'choice_label' => 'title',
                'label' => 'Choisissez une suite',
                'placeholder' => 'Choisissez une suite',
            ]);
        };

        $builder->get('establishment')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $establishment = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $establishment);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}

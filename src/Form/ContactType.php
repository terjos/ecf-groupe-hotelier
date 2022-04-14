<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Votre nom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre nom',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Votre nom doit comporter au moins {{ limit }} caractères',
                        'max' => 50,
                    ]),
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Votre prénom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre prénom',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Votre prénom doit comporter au moins {{ limit }} caractères',
                        'max' => 50,
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'required' => true,
                'attr' => ['autocomplete' => 'email'],
                'constraints' => [
                    new Email([
                        'message' => 'Veuillez entrer un email valide',
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez entrer un email',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre email doit comporter au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('subject', ChoiceType::class, [
                'choices' => [
                    'Je souhaite poser une réclamation' => 'Je souhaite poser une réclamation',
                    'Je souhaite commander un service supplémentaire' => 'Je souhaite commander un service supplémentaire',
                    'Je souhaite en savoir plus sur une suite' => 'Je souhaite en savoir plus sur une suite',
                    'J’ai un souci avec cette application' => 'J’ai un souci avec cette application',
                ],
                'label' => 'Sujet',
                'required' => true,
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un message',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre message doit comporter au moins {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('envoyer', SubmitType::class);
    }
}

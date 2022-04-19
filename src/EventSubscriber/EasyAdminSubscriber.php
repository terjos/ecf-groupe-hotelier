<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

  class EasyAdminSubscriber implements EventSubscriberInterface
  {
      private $entityManager;
      private $passwordHasher;

      public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
      {
          $this->entityManager = $entityManager;
          $this->passwordHasher = $passwordHasher;
      }

      public static function getSubscribedEvents(): array
      {
          return [
              BeforeEntityPersistedEvent::class => ['addUser']
          ];
      }

      public function addUser(BeforeEntityPersistedEvent $event): void
      {
          $entity = $event->getEntityInstance();

          if (!($entity instanceof User)) {
              return;
          }
          $pass = $entity->getPlainPassword();
          $entity->setPassword(
              $this->passwordHasher->hashPassword(
                  $entity,
                  $pass
              )
          );
          $this->entityManager->persist($entity);
          $this->entityManager->flush();
      }
  }

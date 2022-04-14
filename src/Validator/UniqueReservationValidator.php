<?php

namespace App\Validator;


use App\Repository\ReservationRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueReservationValidator extends ConstraintValidator
{

    private ReservationRepository $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function validate($resa, Constraint $constraint)
    {

        if ($resa->getRoom() === null || $resa->getStartAt() === null || $resa->getEndAt() === null) {
            return;
        }

        $resas = $this->reservationRepository->findBydate($resa->getRoom()->getId(), $resa->getStartAt(), $resa->getEndAt());

        if (count($resas) === 0) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ valueStart }}', $resas[0]->getStartAt()->format('d/m/Y'))
            ->setParameter('{{ valueEnd }}', $resas[0]->getEndAt()->format('d/m/Y'))
            ->addViolation();
    }
}

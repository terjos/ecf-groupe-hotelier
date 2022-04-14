<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('réservation')
            ->setEntityLabelInPlural('réservations');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('room', 'Suite'))
            ->add(DateTimeFilter::new('startAt', 'Date de début'))
            ->add(DateTimeFilter::new('endAt', 'Date de fin'));
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if ($this->isGranted('ROLE_ADMIN')) {
            return $queryBuilder;
        }

        $queryBuilder
            ->leftJoin('entity.room', 'r')
            ->leftJoin('r.establishment', 'establishment')
            ->andWhere('establishment.id = :id')
            ->setParameter('id', $this->getUser()->getEstablishment());
        return $queryBuilder;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('room', 'Suite');
        yield TextField::new('user.email', 'Client');
        yield DateField::new('startAt', 'Date de début')->renderAsText();
        yield DateField::new('endAt', 'Date de fin')->renderAsText();
    }
}

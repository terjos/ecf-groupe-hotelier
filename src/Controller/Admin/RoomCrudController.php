<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Form\PictureType;
use App\Form\ReservationType;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Vich\UploaderBundle\Form\Type\VichImageType;

#[IsGranted('ROLE_GERANT')]
class RoomCrudController extends AbstractCrudController
{
    /**
     * @var string
     */
    private $uploadImages;

    public function __construct(string $uploadImages)
    {
        $this->uploadImages = $uploadImages;
    }

    public static function getEntityFqcn(): string
    {
        return Room::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('suite')
            ->setEntityLabelInPlural('suites');
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
            ->andWhere('entity.establishment = :establishment')
            ->setParameter('establishment', $this->getUser()->getEstablishment());
        return $queryBuilder;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title', 'Non de la suite');
        yield TextareaField::new('description', 'Description')->onlyOnForms();
        yield AssociationField::new('establishment', 'Établissement')->setPermission('ROLE_ADMIN');
        yield MoneyField::new('price', 'Prix')->setCurrency('EUR')->setStoredAsCents(false);
        yield TextField::new('bookingLink', 'Lien de réservation Booking');
        yield TextField::new('featuredImageFile', 'Image')->setFormType(VichImageType::class)->onlyOnForms();
        yield ImageField::new('featuredImageName', 'Image')
            ->setBasePath($this->uploadImages)
            ->hideOnForm();
        yield CollectionField::new('Pictures', 'Galerie')
            ->setEntryType(PictureType::class)
            ->setCustomOption('renderExpanded', true)
            ->onlyOnForms();
        yield CollectionField::new('reservations', 'Réservation')
            ->setEntryType(ReservationType::class)
            ->setTemplatePath('admin/reservation_collection.html.twig')
            ->renderExpanded(true)
            ->setEntryIsComplex(true)
            ->onlyOnDetail();
    }

    public function createEntity(string $entityFqcn)
    {
        $room = new Room();
        $room->setEstablishment($this->getUser()->getEstablishment());

        return $room;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}

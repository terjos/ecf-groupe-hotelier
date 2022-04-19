<?php

namespace App\Controller\Admin;

use App\Entity\User;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

#[IsGranted('ROLE_GERANT')]
class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('utilisateur')
            ->setEntityLabelInPlural('utilisateurs');
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
            ->andWhere('entity.id = :id')
            ->setParameter('id', $this->getUser()->getId());
        return $queryBuilder;
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('lastName', 'Nom');
        yield TextField::new('firstName', 'Prénom');
        yield EmailField::new('email', 'Email')->onlyOnForms();
        yield TextField::new('plainPassword', 'Mot de passe')
            ->setFormType(PasswordType::class)
            ->onlyWhenCreating();
        yield AssociationField::new('establishment', 'Établissement')
            ->onlyOnForms()
            ->setPermission('ROLE_ADMIN');
        yield ChoiceField::new('roles')
            ->setPermission('ROLE_ADMIN')
            ->setChoices(
                [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Gérant' => 'ROLE_GERANT',
                    'Client' => 'ROLE_CLIENT',
                ]
            )
            ->allowMultipleChoices()
            ->renderExpanded()
            ->renderAsBadges();
    }
}

<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Security\Core\Security;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;


class TagAdmin extends AbstractAdmin
{
    private $security;

    public function __construct(?string $code = null, ?string $class = null, ?string $baseControllerName = null, Security $security) 
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->security = $security;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('name', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('name');
    }

    protected function configureListFields(ListMapper $list): void
    {        
        $list->addIdentifier('name');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('name');
    }

    protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
    {
        $query = parent::configureQuery($query);

        $user = $this->security->getUser();

        $rootAlias = current($query->getRootAliases());

        $query->andWhere(
            $query->expr()->eq($rootAlias . '.user_id', ':user')
        );
        $query->setParameter('user', $user->getId());

        return $query;
    }

    protected function prePersist($tag): void
    {
        parent::prePersist($tag);
        $user = $this->security->getUser();
        $tag->setUserId($user);
    }

}
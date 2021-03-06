<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use App\Entity\Tag;
use App\Entity\Link;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\Security\Core\Security;

class LinkAdmin extends AbstractAdmin
{
    private $security;

    public function __construct(?string $code = null, ?string $class = null, ?string $baseControllerName = null, Security $security) 
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->security = $security;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $builder = $formMapper->getFormBuilder();
        $ff = $builder->getFormFactory();
        $formMapper->getFormBuilder()->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($ff) {
            $data = $event->getData();
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://' ;
        
            $data['shortened'] = $protocol . $_SERVER['SERVER_NAME']. '/'. $data['url_key'];
            $event->setData($data);

            $event->getForm()->add($ff->createNamed('shortened', TextType::class, $data,['auto_initialize' => false, 'data_class' => null]));
        });

        $formMapper
        ->add('original', TextType::class)
            ->add('url_key', TextType::class)
            ->add('shortened', null, ['disabled' => true])
            ->add('tags', ModelType::class, [
                'class' => Tag::class,
                'multiple' => true,
                'property' => 'name',
                'required' => false,
                'by_reference' => false
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('original');
    }

    protected function configureListFields(ListMapper $list): void
    {        
        $list->add('original')
        ->add('url_key')
        ->add('shortened')
        ->add('visits')
        ->add('tags', ModelType::class, array('associated_property' => 'name'))
        ->add('_action', 'actions', array(
            'actions' => array(
            'show' => array(),
            'edit' => array(),
            'delete' => array(),
            )
        )
        );
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('original')
        ->add('url_key')
        ->add('visits')
        ->add('shortened')
        ->add('tags', 'array');
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

    protected function prePersist($link): void
    {
        parent::prePersist($link);
        $user = $this->security->getUser();
        $link->setUserId($user);
    }

    public function toString(object $object): string
    {
        return $object instanceof Link
            ? $object->getOriginal()
            : 'Link'; // shown in the breadcrumb on the create view
    }
}
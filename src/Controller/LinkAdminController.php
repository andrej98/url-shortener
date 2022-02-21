<?php

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Bridge\Exporter\AdminExporter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class LinkAdminController extends CRUDController
{
    // public function listAction(Request $request): Response
    // {
    //     // $id = $request->get($this->user->admin->getIdParameter());

    //     // $securityContext = $this->get('security.context');
    //     // if (!$securityContext->isGranted('ROLE_ADMIN')) {

    //     //     $adminId = $securityContext->getToken()->getUser()->getId();

    //     //     $accessGranted = 0;//here you should check if user with adminId can edit product with $id

    //     //     if (!$accessGranted) {
    //     //         throw new AccessDeniedException(sprintf('Admin ID %s has no access to product with id %s', $adminId, $id));
    //     //     }
    //     // }

    //     return parent::listAction($id);
    // }
}
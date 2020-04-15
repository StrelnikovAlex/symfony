<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Entity\Tag;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class StuffController extends AbstractController
{
/**
 * @IsGranted("ROLE_USER")
 * @Route("/stuff", name="my_stuff")
 */

    public function show()
    {
        $user = $this->getUser()->getId();

        $tickets = $this->getDoctrine()
            ->getRepository(Tickets::class);
            // ->findBy(
            //     ['assign' => $user]



        return $this->render('stuff.html.twig', [
            'tickets' => $tickets
        ]);
    }
}

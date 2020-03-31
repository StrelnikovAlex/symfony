<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Form\TicketsType;
use App\Repository\TicketsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tickets")
 */
class TicketsController extends AbstractController
{
    /**
     * @Route("/", name="tickets_index", methods={"GET"})
     */
    public function index(TicketsRepository $ticketsRepository): Response
    {
        return $this->render('tickets/index.html.twig', [
            'tickets' => $ticketsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tickets_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ticket = new Tickets();
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('tickets_index');
        }

        return $this->render('tickets/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tickets_show", methods={"GET"})
     */
    public function show(Tickets $ticket): Response
    {
        return $this->render('tickets/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tickets_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tickets $ticket): Response
    {
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tickets_index');
        }

        return $this->render('tickets/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tickets_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tickets $ticket): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tickets_index');
    }
}

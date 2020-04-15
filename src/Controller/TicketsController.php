<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Entity\Tag;
use App\Entity\Projects;
use App\Entity\Comment;
use App\Form\TicketsType;
use App\Repository\TicketsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProjectsType;
use App\Repository\ProjectsRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
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
        $projectId = $request->query->get('project_id');
        $ticket = new Tickets();
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $this->getDoctrine()->getRepository(Projects::class)->find($projectId);
            $ticket->setProjectId($project);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);

            $tags_string = $request->request->get('tickets')['tags'];
            $tags = array_map(function ($value) {
                return trim($value);
            }, explode(',', $tags_string));
            foreach ($tags as $tagName) {
                $tag = new Tag();
                $has_tag = $entityManager->getRepository(Tag::class)->findOneBy(
                    ['name' => $tagName]
                );
                if ($has_tag) {
                    $ticket->addTag($has_tag);
                } else {

                    $tag->setName($tagName);
                    $entityManager->persist($tag);
                    $ticket->addTag($tag);

                }
                $entityManager->flush();
            }


            return $this->redirectToRoute('projects_show', ['id' => $projectId]);
        }

        return $this->render('tickets/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tickets_show", methods={"GET", "POST"})
     */
    public function show(Request $request, Tickets $ticket): Response
    {
      $comment = new Comment();
      $ticketId = $request->attributes->get('id');

      $commentForm = $this->createFormBuilder($comment)
           ->add('text', TextareaType::class, ['label' => 'New Comment'])
           ->getForm();

       $commentForm->handleRequest($request);

       if ($commentForm->isSubmitted() && $commentForm->isValid()) {
           $comment = $commentForm->getData();
           $entityManager = $this->getDoctrine()->getManager();

           $comment->setTicket($ticket);

           $entityManager->persist($ticket);
           $entityManager->persist($comment);
           $entityManager->flush();

           echo $ticketId;


           return $this->redirectToRoute('tickets_show', ['id' => $ticketId]);
       }
            return $this->render('tickets/show.html.twig', [
                   'ticket' => $ticket,
                   'comment_form' => $commentForm->createView(),
                   ]);
    }

    /**
     * @Route("/{id}/edit", name="tickets_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tickets $ticket): Response
    {
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);
        $tags_string = $request->request->get('tickets')['tags'];

        $tags = array_map(function ($value) {
            return trim($value);
        }, explode(',', $tags_string));

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            foreach($ticket->getTags() as $tag) {
                $ticket->removeTag($tag);
            }

            foreach ($tags as $tagName) {

                $tag = new Tag();
                $has_tag = $entityManager->getRepository(Tag::class)->findOneBy(
                    ['name' => $tagName]
                );
                if ($has_tag) {
                    $ticket->addTag($has_tag);
                } else {

                    $tag->setName($tagName);
                    $entityManager->persist($tag);
                    $ticket->addTag($tag);
                }
            }
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

    /**
     * @Route("/{id}/delete_comment", name="comment_delete")
     */
    public function delete_comment(Request $request, $id): Response
    {
        $ticketId = $request->query->get('ticket_id');
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository(Comment::class)->find($id);

        if (!$comment) {
            throw $this->createNotFoundException(
                'No comment found for id '.$id
            );
        }

        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('tickets_show', ['id' => $ticketId]);
    }
}

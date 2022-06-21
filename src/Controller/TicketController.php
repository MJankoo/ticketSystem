<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Service\TicketService;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

class TicketController extends AbstractController
{

    private TicketService $ticketService;

    public function __construct(TicketService $ticketService) {
        $this->ticketService = $ticketService;
    }

    #[Route('/tickets/new', name: 'new_ticket')]
    public function newTicketAction(Request $request): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
                $ticket = $form->getData();

                $uploadedAttachment = $form->get('attachment')->getData();
                try {
                    $this->ticketService->saveTicket($ticket, $uploadedAttachment);
                    $response = [
                        'message' => 'The ticket has been sent successfully',
                        'type' => 'success'
                    ];
                } catch(InvalidArgumentException $e) {
                    $response = [
                        'message' =>  $e->getMessage(),
                        'type' => 'error'
                    ];
                } catch(FileException | PDOException $e) {
                    $response = [
                        'message' =>  'Something went wrong during file uploading. Please try again later',
                        'type' => 'error'
                    ];
                }
            }
        return $this->renderForm('ticket/new.html.twig', [
            'form' => $form,
            'response' => $response ?? null,
        ]);
    }

    #[Route('/tickets', name: 'tickets_list')]
    public function getTicketsListAction() {
        $tickets = $this->ticketService->getAllTickets();

        return $this->renderForm('ticket/list.html.twig', [
            'tickets' => $tickets,
        ]);
    }
}

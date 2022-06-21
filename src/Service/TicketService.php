<?php


namespace App\Service;

use App\Entity\Ticket;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TicketService
{
    private ManagerRegistry $doctrine;
    private TicketValidator $ticketValidator;
    private FileService $fileService;

    public function __construct(ManagerRegistry $doctrine, TicketValidator $ticketValidator, FileService $fileService) {
        $this->doctrine = $doctrine;
        $this->ticketValidator = $ticketValidator;
        $this->fileService = $fileService;
    }

    public function saveTicket(Ticket $ticket, ?UploadedFile $attachment) {

        $attachmentAddress = "";
        if($attachment) {
            $attachmentAddress = $this->fileService->uploadFile($attachment);
        }

        $isAttachmentProcessedCorrectly = $attachment===null || $attachmentAddress!=="";

        if($isAttachmentProcessedCorrectly && $this->ticketValidator->validateTicket($ticket)) {
            $ticket->setAttachment($attachmentAddress);
            try {
                $entityManager = $this->doctrine->getManager();
                $entityManager->persist($ticket);
                $entityManager->flush();

            } catch(PDOException $e) {
                $this->fileService->removeFile($attachmentAddress);
                throw new PDOException('Could not save '.print_r($ticket, true).' to database.');
            }
        }

    }

    public function getAllTickets() {
        $tickets = $this->doctrine->getRepository(Ticket::class)->findAll();
        return $tickets;
    }

}
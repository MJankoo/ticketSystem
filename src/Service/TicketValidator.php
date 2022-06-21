<?php


namespace App\Service;


use App\Entity\Ticket;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

class TicketValidator
{
    public function validateTicket(Ticket $ticket) {
        $this->checkName($ticket->getName());
        $this->checkSurname($ticket->getName());

        return true;
    }

    private function checkName(?string $name) {
        if($name===null || strlen($name) <= 0 || strlen($name) > 255)
            throw new InvalidArgumentException('Name must not be empty');
    }

    private function checkSurname(?string $surname) {
        if($surname===null || strlen($surname) <= 0 || strlen($surname) > 255)
            throw new InvalidArgumentException('Surname must not be empty');
    }
}
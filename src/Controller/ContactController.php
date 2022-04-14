<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();

            $message = (new Email())
                ->from($contactFormData['email'])
                ->to('admin@exemple.com')
                ->subject($contactFormData['subject'])
                ->text(
                    'Sender: ' . $contactFormData['email'] . \PHP_EOL .
                        $contactFormData['message'],
                    'text/plain'
                );
            $mailer->send($message);
            $this->addFlash('success', 'Votre email à bien été envoyé.');

            return $this->redirectToRoute('app_establishment_index');
        }

        return $this->render('contact/index.html.twig', [
            'mail_form' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class BaseController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig', [
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $this->addFlash('notice','Message envoyé');
                $email = (new Email())
                ->from($form->get('email')->getData())
                ->to('lucie.delannoy@epsi.fr')
                ->subject($form->get('sujet')->getData())
                ->text($form->get('message')->getData());
              
                $mailer->send($email);
                return $this->redirectToRoute('contact');
            }
        }
        return $this->render('base/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/apropos', name: 'apropos')]
    public function apropos(): Response
    {
        return $this->render('base/apropos.html.twig', [
        ]);
    }

    #[Route('/mentionlegales', name: 'mentionlegales')]
    public function mentionlegales(): Response
    {
        return $this->render('base/mentionlegales.html.twig', [
        ]);
    }
}


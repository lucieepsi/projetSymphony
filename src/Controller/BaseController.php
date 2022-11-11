<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Contact;

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
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){   
                
                $contact->setDateEnvoi(new \Datetime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();
              
                $this->addFlash('notice','Message envoyé');
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


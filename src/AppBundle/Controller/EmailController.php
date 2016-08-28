<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Email;
use AppBundle\Form\EmailType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{
    /**
     * @Route("/{id}/addEmail", requirements={"id" = "\d+"})
     * @Template("AppBundle:Components:emailForm.html.twig")
     */
    public function addAction(Request $request, $id)
    {
        $email = new Email();
        $contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        $email->setContact($contact);
        $contact->addEmail($email);

        $form = $this->createForm(new EmailType(), $email);
        $form->add('submit','submit');

        $form->handleRequest($request);

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($email);
            $em->flush();

            return $this->redirectToRoute('app_contact_modify', ['id' => $id]);
        }
        return ['form' => $form->createView(), 'id' => $id];
    }

    /**
     * @Route("/{id}/modifyEmail/{e_id}", requirements={"id" = "\d+", "e_id" = "\d+"})
     * @Template("AppBundle:Components:emailForm.html.twig")
     */
    public function modifyAction(Request $request, $id, $e_id)
    {

        $email = $this->getDoctrine()->getRepository('AppBundle:Email')->find($e_id);
        if (!$email) {
            throw $this->createNotFoundException('Email not found');
        }
        $contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        $form = $this->createForm(new EmailType(), $email);
        $form->add('submit', 'submit');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('app_contact_modify', ['id' => $id]);
        }
        return ['form' => $form->createView(), 'id' => $id];
    }

    /**
     * @Route("/{id}/deleteEmail/{e_id}", requirements={"id" = "\d+", "e_id" = "\d+"})
     */
    public function deleteAction($id, $e_id)
    {
        $email = $this->getDoctrine()->getRepository('AppBundle:Email')->find($e_id);
        if (!$email){
            throw $this->createNotFoundException('Email not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($email);
        $em->flush();

        return $this->redirectToRoute('app_contact_modify', ['id' => $id]);
    }
}

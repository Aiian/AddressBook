<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Entity\Contact;
use AppBundle\Form\AddressType;
use AppBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    /**
     * @Route("/new")
     * @Template("AppBundle:Contact:newForm.html.twig")
     */
    public function newAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createForm(new ContactType(), $contact);
        $form->add('submit','submit');

        $form->handleRequest($request);

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
        }
        return ['form' => $form->createView(), 'contact' => $contact];
    }

    /**
     * @Route("/{id}", requirements={"id" = "\d+"})
     * @Template
     */
    public function showAction($id)
    {
        $contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id);
        if (!$contact){
            throw $this->createNotFoundException('Contact not found');
        }

        return ['contact' => $contact];
    }

    /**
     * @Route("/{id}/modify", requirements={"id" = "\d+"})
     * @Template("AppBundle:Contact:form.html.twig")
     */
    public function modifyAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id);
        if (!$contact){
            throw $this->createNotFoundException('Contact not found');
        }

        $form = $this->createForm(new ContactType(), $contact);
        $form->add('submit','submit');

        $form->handleRequest($request);

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('app_contact_show', ['id' => $id]);
        }
        return ['form' => $form->createView(), 'contact' => $contact];
    }

    /**
     * @Route("/{id}/delete", requirements={"id" = "\d+"})
     */
    public function deleteAction($id)
    {
        $contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id);
        if (!$contact){
            throw $this->createNotFoundException('Contact not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($contact);
        $em->flush();

        return $this->redirectToRoute('app_contact_showall');
    }

    /**
     * @Route("/")
     * @Template
     */
    public function showAllAction()
    {
        $contacts = $this->getDoctrine()->getRepository('AppBundle:Contact')->findAll();

        return ['contacts' => $contacts];
    }
}

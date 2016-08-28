<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Phone;
use AppBundle\Form\PhoneType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class PhoneController extends Controller
{
    /**
     * @Route("/{id}/addPhone", requirements={"id" = "\d+"})
     * @Template("AppBundle:Components:phoneForm.html.twig")
     */
    public function addAction(Request $request, $id)
    {
        $phone = new Phone();
        $contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        $phone->setContact($contact);
        $contact->addPhone($phone);

        $form = $this->createForm(new PhoneType(), $phone);
        $form->add('submit','submit');

        $form->handleRequest($request);

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($phone);
            $em->flush();

            return $this->redirectToRoute('app_contact_modify', ['id' => $id]);
        }
        return ['form' => $form->createView(), 'id' => $id];
    }

    /**
     * @Route("/{id}/modifyPhone/{p_id}", requirements={"id" = "\d+", "p_id" = "\d+"})
     * @Template("AppBundle:Components:phoneForm.html.twig")
     */
    public function modifyAction(Request $request, $id, $p_id)
    {

        $phone = $this->getDoctrine()->getRepository('AppBundle:Phone')->find($p_id);
        if (!$phone) {
            throw $this->createNotFoundException('Phone not found');
        }
        $contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        $form = $this->createForm(new PhoneType(), $phone);
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
     * @Route("/{id}/deletePhone/{p_id}", requirements={"id" = "\d+", "p_id" = "\d+"})
     */
    public function deleteAction($id, $p_id)
    {
        $phone = $this->getDoctrine()->getRepository('AppBundle:Address')->find($p_id);
        if (!$phone){
            throw $this->createNotFoundException('Phone not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($phone);
        $em->flush();

        return $this->redirectToRoute('app_contact_modify', ['id' => $id]);
    }
}

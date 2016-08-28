<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Form\AddressType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class AddressController extends Controller
{
    /**
     * @Route("/{id}/addAddress", requirements={"id" = "\d+"})
     * @Template("AppBundle:Components:addressForm.html.twig")
     */
    public function addAction(Request $request, $id)
    {
        $address = new Address();
        $contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        $address->setContact($contact);
        $contact->addAddress($address);

        $form = $this->createForm(new AddressType(), $address);
        $form->add('submit','submit');

        $form->handleRequest($request);

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute('app_contact_modify', ['id' => $id]);
        }
        return ['form' => $form->createView(), 'id' => $id];
    }

    /**
     * @Route("/{id}/modifyAddress/{a_id}", requirements={"id" = "\d+", "a_id" = "\d+"})
     * @Template("AppBundle:Components:addressForm.html.twig")
     */
    public function modifyAction(Request $request, $id, $a_id)
    {

        $address = $this->getDoctrine()->getRepository('AppBundle:Address')->find($a_id);
            if (!$address) {
                throw $this->createNotFoundException('Address not found');
            }
        $contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id);
            if (!$contact) {
                throw $this->createNotFoundException('Contact not found');
            }

        $form = $this->createForm(new AddressType(), $address);
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
     * @Route("/{id}/deleteAddress/{a_id}", requirements={"id" = "\d+", "a_id" = "\d+"})
     */
    public function deleteAction($id, $a_id)
    {
        $address = $this->getDoctrine()->getRepository('AppBundle:Address')->find($a_id);
        if (!$address){
            throw $this->createNotFoundException('Address not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();

        return $this->redirectToRoute('app_contact_modify', ['id' => $id]);
    }
}

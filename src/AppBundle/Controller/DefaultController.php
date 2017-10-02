<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\ShopOrder;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll();

        return $this->render('default/index.html.twig', array(
            'products' => $products,
        ));
    }


    /**
     * Finds and displays a product entity.
     *
     * @Route("/checkout", name="checkout")
     * @Method({"GET", "POST"})
     */
    public function checkoutAction(Request $request)
    {
        // Get product in session, if any.
        $session = $request->getSession();
        $product = $session->get( 'product' );
        // If no product in session.
        if ( !$product ) {
            $this->addFlash(
                'notice',
                'No product chosen to buy yet!'
            );

            return $this->redirectToRoute( 'homepage' );
        }
        

        // Create a new shop order form.
        $shopOrder = new Shoporder();
        // Prepopulate some fields on the order object.
        $shopOrder->setProduct( $product );
        $shopOrder->setOrderTotalUsd( $product->getPrice() );
        $shopOrder->setOrderPaid( false );
        $shopOrder->setOrderStatus( 'pending_payment' );

        // Create form for the user to fill in his order info.
        $form = $this->createForm('AppBundle\Form\ShopOrderType', $shopOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopOrder);
            $em->flush();

            return $this->redirectToRoute('shoporder_show', array('id' => $shopOrder->getId()));
        }

        return $this->render('default/checkout.html.twig', array(
            'tobtc_endpoint' => $this->container->getParameter('tobtc_endpoint'),
            'shopOrder' => $shopOrder,
            'checkout_form' => $form->createView(),
        ));
    }


    public function toBTC( $usd_price ) {
        $endpointToBTC = $this->container->getParameter('tobtc_endpoint');
        $endpointToBTC .= $usd_price;
        $response = \Requests::get( $endpointToBTC );
        // Store Bitcoin price in Product.
        return $response->body;
    }

}

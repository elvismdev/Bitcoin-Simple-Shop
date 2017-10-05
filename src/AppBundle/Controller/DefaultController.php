<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\ShopOrder;
use AppBundle\Service\BlockchainDotInfoService;

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
     * Do the checkout, sets an order.
     *
     * @Route("/checkout", name="checkout")
     * @Method({"GET", "POST"})
     */
    public function checkoutAction(Request $request, BlockchainDotInfoService $blockchainInfo)
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
        $form = $this->createForm('AppBundle\Form\ShopOrderType', $shopOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Set some other order info.
            $shopOrder->setProduct( $product );
            $shopOrder->setOrderPaid( false );
            $shopOrder->setOrderStatus( 'pending_payment' );
            $shopOrder->setOrderTotalUsd( $product->getPrice() );
            // Set final total in BTC for this order.
            $totalBTC = $blockchainInfo->toBTC( $product->getPrice() );
            $shopOrder->setOrderTotalBtc( $totalBTC );

            $em = $this->getDoctrine()->getManager();
            $em->persist($shopOrder);
            $em->flush();

            return $this->redirectToRoute('order_pay', array('id' => $shopOrder->getId()));
        }

        return $this->render('default/checkout.html.twig', array(
            'tobtc_endpoint' => $this->container->getParameter('tobtc_endpoint'),
            'checkout_form' => $form->createView(),
        ));
    }


    /**
     * Pay for the order.
     *
     * @Route("/pay/{id}", name="order_pay")
     * @Method("GET")
     */
    public function payAction( ShopOrder $shopOrder, Request $request ) {
        // Get Blockchain.info parameters.
        $blockchainDotInfoParams = $this->container->getParameter('blockchain_dot_info');
        // Create callback url.
        $callback_url = $request->getSchemeAndHttpHost() . '/thankyou/' . $shopOrder->getId() . '/' . $blockchainDotInfoParams['secret'];
        // Set parameters for Blockchain.info address request.
        $params = 'xpub=' . $blockchainDotInfoParams['xpub'] . '&callback=' . urlencode( $callback_url ) . '&key=' . $blockchainDotInfoParams['api_key'];
        // Get address to pay from Blockchain.info
        $response = \Requests::get( $blockchainDotInfoParams['receive_url'] . '?' . $params );


        // print_r($response->body);
        // die();

        return $this->render('default/order_pay.html.twig', array(
            'shopOrder' => $shopOrder,
            'pay_to' => '18iyJANMcUoR4ZZNjv3W6nzvsRaPdw7Ck4'       // To change for API response
        ));
    }

}

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
     * @Route("/checkout/{price_id}", name="checkout")
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


        // Get price ID if any.
        $priceId = $request->get('price_id');
        // Get the price option from DB.
        $em = $this->getDoctrine()->getManager();
        $productObj = $em->getRepository('AppBundle:Product')->find( $product->getId() );
        $priceOpt = $em->getRepository('AppBundle:PriceOption')->find( $priceId );


        // Create a new shop order form.
        $shopOrder = new Shoporder();
        $form = $this->createForm('AppBundle\Form\ShopOrderType', $shopOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Set some other order info.
            $shopOrder->addProduct( $productObj );
            $shopOrder->setOrderPaid( false );
            $shopOrder->setOrderStatus( 'pending_payment' );
            $shopOrder->setOrderTotalUsd( $priceOpt->getPrice() );
            // Set final total in BTC for this order.
            $totalBTC = $blockchainInfo->toBTC( $priceOpt->getPrice() );
            $shopOrder->setOrderTotalBtc( $totalBTC );

            $em->persist($shopOrder);
            $em->flush();

            return $this->redirectToRoute('order_pay', array('id' => $shopOrder->getId()));
        }

        return $this->render('default/checkout.html.twig', array(
            'product_price'  => $priceOpt->getPrice(),
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

        // Check if order was already paid, redirect home if true.
        if ( $shopOrder->getOrderPaid() === true ) {
            $this->addFlash(
                'notice',
                'This order was already paid!'
            );

            return $this->redirectToRoute( 'homepage' );
        }

        $products = $shopOrder->getProducts();

        // Create callback url.
        $callback_url = $request->getSchemeAndHttpHost() . '/callback/' . $shopOrder->getId() . '/' . $this->container->getParameter('secret');
        // Set parameters for Blockchain.info address request.
        $params = 'xpub=' . $this->container->getParameter('blockchain_xpub') . '&callback=' . urlencode( $callback_url ) . '&key=' . $this->container->getParameter('blockchain_api_key');
        // Get address to pay from Blockchain.info
        $response = \Requests::get( 'https://api.blockchain.info/v2/receive?' . $params );


        print_r($response->body);
        die();

        return $this->render('default/order_pay.html.twig', array(
            'products'  => $products,
            'shopOrder' => $shopOrder,
            'pay_to'    => '18iyJANMcUoR4ZZNjv3W6nzvsRaPdw7Ck4'       // To change for API response
        ));
    }


    /**
     * Callback URL for Blockchain.info
     *
     * @Route("/callback/{id}/{secret}", name="payment_callback")
     * @Method("GET")
     */
    public function callbackAction( ShopOrder $shopOrder, Request $request ) {

        // Verify the secret word.
        if ( $request->get('secret') != $this->container->getParameter('secret') ) {
            die( 'AYE WHATCHA DOIN THERE!?!?!' );
        } else {
            // Get and process response data from Blockchain.info
            $transactionHash    = $request->get('transaction_hash');
            $address            = $request->get('address');
            $valueInSatoshi     = $request->get('value');
            $valueInBTC         = $valueInSatoshi / 100000000;

            // Update order in DB.
            $shopOrder->setAmountPaid( $valueInBTC );
            if ( $valueInBTC == $shopOrder->getOrderTotalBtc() ) {
                $shopOrder->setDifference( 'No Difference' );
            } else if ( $valueInBTC < $shopOrder->getOrderTotalBtc() ) {
                $shopOrder->setDifference( 'Underpaid' );
            } else {
                $shopOrder->setDifference( 'Overpaid' );
            }
            $shopOrder->setTransactionHash( $transactionHash );
            $shopOrder->setBtcAddressId( $address );
            $shopOrder->setOrderStatus( 'completed' );
            $shopOrder->setOrderPaid( true );

            // Call Doctrine Entity Manager to update/persist data in Order.
            $em = $this->getDoctrine()->getManager();
            $em->persist( $shopOrder );
            $em->flush();


            // Return *ok* for Blockchain.info
            return $this->render( 'default/payment_callback.html.twig' );
        }

        
    }


    /**
     * @Route("/thankyou/{id}", name="thank_you")
     * @Method("GET")
     */
    public function thankYouAction( ShopOrder $shopOrder, Request $request )
    {
        // Set flashbag success message if we come from the order payment page.
        if ( $shopOrder->getOrderPaid() === true ) {
            $this->addFlash(
                'notice',
                'Your order has been placed!'
            );
        }

        return $this->redirectToRoute( 'homepage' );
    }

}

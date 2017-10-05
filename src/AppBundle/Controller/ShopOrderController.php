<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ShopOrder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\BlockchainDotInfoService;

/**
 * Shoporder controller.
 *
 * @Route("shoporder")
 */
class ShopOrderController extends Controller
{
    /**
     * Lists all shopOrder entities.
     *
     * @Route("/", name="shoporder_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $shopOrders = $em->getRepository('AppBundle:ShopOrder')->findAll();

        return $this->render('shoporder/index.html.twig', array(
            'shopOrders' => $shopOrders,
        ));
    }

    /**
     * Creates a new shopOrder entity.
     *
     * @Route("/new", name="shoporder_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $shopOrder = new Shoporder();
        $form = $this->createForm('AppBundle\Form\ShopOrderType', $shopOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopOrder);
            $em->flush();

            return $this->redirectToRoute('shoporder_show', array('id' => $shopOrder->getId()));
        }

        return $this->render('shoporder/new.html.twig', array(
            'shopOrder' => $shopOrder,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a shopOrder entity.
     *
     * @Route("/{id}", name="shoporder_show")
     * @Method("GET")
     */
    public function showAction(ShopOrder $shopOrder)
    {
        $deleteForm = $this->createDeleteForm($shopOrder);

        return $this->render('shoporder/show.html.twig', array(
            'shopOrder' => $shopOrder,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing shopOrder entity.
     *
     * @Route("/{id}/edit", name="shoporder_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ShopOrder $shopOrder, BlockchainDotInfoService $blockchainInfo)
    {

        // Get Product object from order.
        $product = $shopOrder->getProduct();

        // Save product in session.
        $session = $request->getSession();
        $session->set('product', $product);

        // Create edit page forms.
        $deleteForm = $this->createDeleteForm($shopOrder);
        $editForm = $this->createForm('AppBundle\Form\ShopOrderType', $shopOrder);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // Update the total in BTC for this order.
            $totalBTC = $blockchainInfo->toBTC( $product->getPrice() );
            $shopOrder->setOrderTotalBtc( $totalBTC );

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_pay', array('id' => $shopOrder->getId()));
        }

        return $this->render('shoporder/edit.html.twig', array(
            'shopOrder' => $shopOrder,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'tobtc_endpoint' => $this->container->getParameter('tobtc_endpoint')
        ));
    }

    /**
     * Deletes a shopOrder entity.
     *
     * @Route("/{id}", name="shoporder_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ShopOrder $shopOrder)
    {
        $form = $this->createDeleteForm($shopOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shopOrder);
            $em->flush();
        }


        $this->addFlash(
            'notice',
            'Order has been removed!'
        );

        
        // If deleted from admin, redirect to orders lists.
        $path = $this->getRefererPath( $request );
        $regex = '#^/shoporder/(?P<id>[0-9]+)$#';
        if ( preg_match( $regex, $path ) ) return $this->redirectToRoute( 'shoporder_index' );


        return $this->redirectToRoute('homepage');
    }

    /**
     * Creates a form to delete a shopOrder entity.
     *
     * @param ShopOrder $shopOrder The shopOrder entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ShopOrder $shopOrder)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('shoporder_delete', array('id' => $shopOrder->getId())))
        ->setMethod('DELETE')
        ->getForm()
        ;
    }


    /**
     * Get the referer path for a Request.
     * 
     * @param object $request
     * @return string
     */
    private function getRefererPath(Request $request = null)
    {
        if ($request == null)
            $request = $this->getRequest();

        //look for the referer route
        $referer = $request->headers->get('referer');
        $path = str_replace($request->getSchemeAndHttpHost(), '', $referer);

        return $path;
    }

}

<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BlockchainDotInfoService {

    private $container;

    public function __construct( ContainerInterface $container ) {
        $this->container = $container;
    }

    /**
     * Converts from USD to BTC.
     *
     */
    public function toBTC( $usd_price ) {
        $endpointToBTC = $this->container->getParameter('tobtc_endpoint');
        $endpointToBTC .= $usd_price;
        $response = \Requests::get( $endpointToBTC );
        // Return current Bitcoin price for Product.
        return $response->body;
    }

}

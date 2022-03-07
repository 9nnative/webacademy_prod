<?php

namespace App\Controller;

use Symfony\Component\Mercure\Update;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mercure\HubInterface;

class MercureController extends AbstractController
{
    /**
     * @Route("/ping", name="ping")
     */
    public function ping(Request $request, HubInterface $hub) 
    {
        $update = new Update(
            'https://example.com/foo',
            json_encode(['status' => 'OutOfStock'])
        );

        $hub->publish($update);

        return new Response('published!');
    }

    
}
<?php
namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ClientController extends ApiController
{
    /**
    * @Route("/clients", methods="GET")
    */
    public function index(ClientRepository $clientRepository)
    {
        $client = $clientRepository->transformAll();

        return $this->respond($client);
    }

    /**
    * @Route("/clients", methods="POST")
    */
    public function create(Request $request, ClientRepository $clientRepository, EntityManagerInterface $em)
    {
        
        //$request = Request::createFromGlobals();
        //$request = $this->transformJsonBody($request);
        //$request = json_decode($request->getContent(), true);
        //$request = new JsonResponse($request) 
        //$request = $this->getRequest();
        /*$parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }*/
        
        $request = json_decode($request->getContent(), true);
        //$request->request->getContent('name');
        ///$request = $request['name'];
        //$data=$request->request->get('name');
        //$request = $request->getContent();
        //return $this->respond($d);

        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        // validate the name
        if (! $request['name']) {
            return $this->respondValidationError('Please provide a name!');
        }

        if (! $request['email']) {
            return $this->respondValidationError('Please provide a email!');
        }

        if (! $request['gender']) {
            return $this->respondValidationError('Please provide a gender!');
        }
        
        if (! $request['content']) {
            return $this->respondValidationError('Please provide a content!');
        }
        

        // persist the new client
        $client = new Client;
        $client->setName($request['name']);
        $client->setEmail($request['email']);
        $client->setGender($request['gender']);
        $client->setContent($request['content']);
        $em->persist($client);
        $em->flush();

        //return $this->respond($request);
        return $this->respond($clientRepository->transform($client));
    }
}
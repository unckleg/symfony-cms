<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GenusController extends Controller
{
    /**
     * @Route("/genus/{genusName}")
     * @Method("GET")
     *
     */
    public function showAction($genusName)
    {
        $funFact = 'Octopuses can change the color of their body in just three-tenths of a second!';
        
        $funFact = $this->get('markdown.parser')
                ->transform($funFact);

        $genusTrans = [
            [
                'name' => 'Djordje',
                'surname' => 'Stojiljkovic',
                'phone' => '+381605289528'
            ],
            [
                'name' => 'Ivan',
                'surname' => 'Markovic',
                'phone' => '+381605289528'
            ],
        ];

        //Doctrine caching
        $cache = $this->get('doctrine_cache.providers.my_markdown_cache');
        
        $key = md5($funFact);
        if ($cache->contains($key)) {
            $funFact = $cache->fetch($key);
        } else {
            sleep(1);
            $funFact = $this->get('markdown.parser')
                    ->transform($funFact);
            
            $cache->save($key, $funFact);
        } 
        //Caching is working
        
        return $this->render('genus/show.html.twig', [
                'name' => $genusName,
                'funFact' => $funFact,
                'genusTrans' => $genusTrans,
        ]);
    }
    
    /**
     * @Route("/genus/{genusName}/notes", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction($genusName)
    {
        $notes = [
            ['id' => 1, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Octopus asked me a riddle, outsmarted me', 'date' => 'Dec. 10, 2015'],
            ['id' => 2, 'username' => 'AquaWeaver', 'avatarUri' => '/images/ryan.jpeg', 'note' => 'I counted 8 legs... as they wrapped around me', 'date' => 'Dec. 1, 2015'],
            ['id' => 3, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Inked!', 'date' => 'Aug. 20, 2015'],
        ];
        
        $data = [
            'notes' => $notes, 
        ];
        
        return new JsonResponse($data);
    }
}
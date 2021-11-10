<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/home/{genre}", defaults={"genre"=28}, name="home")
     */
    public function index($genre)
    {   
        $responseGenre = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/genre/movie/list?api_key=d4d4c77d98eb04b6179928e26d1974ba&language=en-US'
        );
        $statusCode = $responseGenre->getStatusCode();
        $contentType = $responseGenre->getHeaders()['content-type'][0];
        $content = $responseGenre->getContent();


        $responseMoviesByGenre = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/discover/movie?api_key=d4d4c77d98eb04b6179928e26d1974ba&language=en-US&with_genres='.$genre
        );

        $contentMoviesByGenre = $responseMoviesByGenre->getContent();

        return $this->render('home/home.html.twig', ['genres' => json_decode($content), 'moviesByGenre' => json_decode($contentMoviesByGenre)]);
    }

    /**
     * @Route("/details/{id}", name="detail")
     */
    public function getDetails($id)
    {   
       
        $response= $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/movie/'.$id.'/videos?api_key=d4d4c77d98eb04b6179928e26d1974ba&language=en-US'
        );
        

        $content = $response->getContent();

        return $this->render('detail/detail.html.twig', ['details' => json_decode($content)]);
    }

}
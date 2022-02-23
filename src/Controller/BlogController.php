<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    private const POSTS=[
        [
            'id'=>'1',
            'slug'=>'hello-world',
            'title'=>'Hello world'
        ],
        [
            'id'=>'2',
            'slug'=>'another-post',
            'title'=>'This is another post!'
        ],
        [
            'id'=>'3',
            'slug'=>'last-example',
            'title'=>'This is the last example'
        ]
    ];

    /**
     * @Route("/{page}", name="blog_list", defaults={"page"=5}, requirements={"page"="\d+"})
     */
    public function list($page, Request $request, ManagerRegistry $doctrine){
        $repository=$doctrine->getRepository(BlogPost::class);
        $items=$repository->findAll();
//        return $this->json($items);
//        var_dump($request->cookies);
        $limit=$request->get('limit', 10);
        return $this->json([
            "page"=>$page,
            "limit"=> $limit,
            "data"=>array_map(function (BlogPost $item){
                return $this->generateUrl('blog_by_slug', ["slug"=>$item->getSlug()]);
            },$items
            )
        ]);
    }

    /**
     * @Route("/post/{id}", name="blog_by_id", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function post(ManagerRegistry $doctrine, BlogPost $post){
        return $this->json($post);
//        $item=$doctrine->getRepository(BlogPost::class)->find($id);
//        return $this->json($item);
//        return $this->json(self::POSTS[array_search($id, array_column(self::POSTS, 'id'))]);
    }

    /**
     * @Route("/post/{slug}", methods={"GET"}, name="blog_by_slug");
     */
    public function postBySlug(ManagerRegistry $doctrine, $slug){
//        echo 'SLUG';
        $item=$doctrine->getRepository(BlogPost::class)->findOneBy(["slug"=>$slug]);
        return $this->json($item);
        return $this->json(self::POSTS[array_search($slug, array_column(self::POSTS, 'slug' ))]
    );
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine){
//        /** @var Serializer $serializer */
//        $encoder=[new JsonEncoder()];
//        $normalizer=[new ObjectNormalizer()];
//        $serializer=new Serializer($encoder, $normalizer);
//        $blogPost=$serializer->deserialize($request, BlogPost::class, 'json');
//        $reqJSON=$this->json($request);
//        $serializer=$this->get('serializer');
//        $data='{"test":"Bartledoo"}';
        $blogPost=$serializer->deserialize($request->getContent(), BlogPost::class, 'json');


        $em=$doctrine->getManager();
        $em->persist($blogPost);
        $em->flush(); //Saves all the data to db

        return $this->json($blogPost);
//        return new JsonResponse($blogPost);
//        return new JsonResponse(json_decode($request->getContent()));
    }

    /**
     * @Route("/post/{id}", name="blog_delete", methods={"DELETE"});
     */
    public function delete(BlogPost $post, ManagerRegistry $doctrine){
        $em=$doctrine->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

































}


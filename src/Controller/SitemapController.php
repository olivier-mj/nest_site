<?php

namespace App\Controller;


use App\Repository\PostRepository;
use App\Controller\AbstractController;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'sitemap', defaults: ['_format' => "xml"])]
    public function sitemap(Request $request, PostRepository $posts, CategoryRepository $categories): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];

        $urls[] = ['loc' => $this->generateUrl('page.home')];
        $urls[] = ['loc' => $this->generateUrl('blog.index')];

        foreach ($posts->findForSitemap() as $post) {
            $urls[] = [
                'loc' => $this->generateUrl('blog.show', ['slug' => $post->getSlug(), 'id' => $post->getId()]),
                'lastmod' => $post->getCreatedAt()->format('Y-m-d')
            ];
        }

        foreach ($categories->findForBlog() as $category) {
            $urls[] = [
                'loc' => $this->generateUrl('categories.show', ['slug' => $category->getSlug(),
                    'id' => $category->getId()]),

            ];
        }


//        $urls[] = ['loc' => $this->generateUrl('media.index')];
        // $urls[] = ['loc' => $this->generateUrl('page.services')];
        $urls[] = ['loc' => $this->generateUrl('page.about')];
        $urls[] = ['loc' => $this->generateUrl('page.contact')];


        $response = new Response(
            $this->renderView('page/sitemap.xml.twig', [
                'urls' => $urls,
                "hostname" => $hostname
            ]), 200
        );

        $response->headers->set('Content-Type', 'text/xml');
        return  $response;

    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\BlogType;
use App\Services\Cache;
use App\Form\BlogNewType;
use App\Entity\Attachment;
use App\Form\BlogEditType;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManager;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Repository\VideoRepository;
use App\Repository\CommentRepository;
use App\Controller\AbstractController;
use App\Repository\CategoryRepository;
use App\Repository\AttachmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServiceConfigurator;

#[Route('/admin')]
class BlogController extends AbstractController
{

    public function __construct(
        private PostRepository $postRepository,
        private CategoryRepository $categoryRepository,
        private TagRepository $tagRepository,
        private CommentRepository $commentRepository,
        private VideoRepository $videoRepository,
    ) {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->commentRepository = $commentRepository;
        $this->videoRepository = $videoRepository;
    }


    #[Route('/', name: 'admin.dashboard')]
    public function index(): Response
    {
        $user = $this->getUserOrThrow()->getId();
        return $this->render('admin/index.html.twig', [
            'post' => $this->postRepository->findForAdmin($user),
            'category' => $this->categoryRepository->findAll(),
            'tags' => $this->tagRepository->findAll(),
            'comments' => $this->commentRepository->findAll(),
            'videos' => $this->videoRepository->findAll(),
        ]);
    }

    #[Route('/blog', name: 'admin.blog_index', methods: ['GET'])]
    public function indexPost(PaginatorInterface $paginator, Request $request, PostRepository $postRepository): Response
    {
        $user = $this->getUserOrThrow();


        $pagination = $paginator->paginate(
            $postRepository->findForAdmin(),
            $request->query->getInt('page', 1),
            13
        );
        return $this->render('admin/blog/index.html.twig', [
            'posts' => $postRepository->findForAdmin($user->getId()),
            // 'posts' => $postRepository->findForAdmin(),
            'pagination' => $pagination
        ]);
    }

    #[Route('/blog/new', name: 'admin.blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,  Cache $cache): Response
    {
        $post = new Post();
        $form = $this->createForm(BlogType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUserOrThrow();
            $post->setUser($user);
            $entityManager->persist($post);
            $entityManager->flush();
            $cache->invalidateTags(['posts']);
            $cache->delete('posts');
            return $this->redirectToRoute('admin.blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/blog/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/blog/{id}', name: 'admin.blog_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('admin/blog/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/blog/{id}/edit', name: 'admin.blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager, Cache $cache): Response
    {
        $form = $this->createForm(BlogType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            $data = $form->getData();

            $entityManager->flush();
            $cache->invalidateTags(['blog']);
            $cache->delete('blog');

            return $this->redirectToRoute('admin.blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/blog/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('blog/{id}', name: 'admin.blog_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.blog_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/api/tags', name: 'admin.api_tags', methods: ['GET'])]
    public function api(Request $request, TagRepository $repository, SerializerInterface $serializer): Response
    {
        $query = (string)$request->query->get('q');

        return $this->json(
            json_decode(
                $serializer->serialize(
                    $repository->search($query),
                    'json',
                    [AbstractNormalizer::IGNORED_ATTRIBUTES => ['posts']]
                )
            )
        );
    }
}

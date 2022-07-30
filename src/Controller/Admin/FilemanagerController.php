<?php

namespace App\Controller\Admin;

use App\Entity\Attachment;
use App\Services\FileUploader;
use App\Controller\AbstractController;
use App\Repository\AttachmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin')]
class FilemanagerController extends AbstractController
{

    public function __construct(
        private string  $galleryDir,
        private string  $uploadDir,

    ) {
        $this->galleryDir = $galleryDir;
        $this->uploadDir = $uploadDir;
    }

    #[Route('/filemanager', name: 'admin.filemanager.index')]
    public function filemanger(AttachmentRepository $attachment): Response
    {
        return $this->render('admin/filemanager/index.html.twig', [
            'images' => $attachment->findAll(),
            'galleryDir' => $this->galleryDir
        ]);
    }

    #[Route('/filemanager/item/{id}/', name: 'admin.filemanager.show', requirements: ['id' => '[0-9\-]*'])]
    public function filemangerShow(AttachmentRepository $attachment, int $id): Response
    {
        return $this->render('admin/filemanager/show.html.twig', [
            'image' => $attachment->findOneBy(['id' => $id])
        ]);
    }

    #[Route('/filemanager/upload/image', name: 'admin.filemanager.uploadImage', methods: ['POST'])]
    public function filemanagerUpload(Request $request, FileUploader $fileUpload, EntityManagerInterface $entityManager): Response
    {
        $file = $request->files->get('file-0');


        if ($file !== false) {
            $attachment = new Attachment();

            $fileName = $fileUpload->upload($file);
            // if ($file instanceof File) {
            // $fileSize = $file->getSize();
            // } else {
            $fileSize = 0;
            // }

            $attachment
                ->setFileName($fileName)
                ->setFileSize($fileSize);

            $entityManager->persist($attachment);
            $entityManager->flush();
            $result = [
                "result" => [
                    [
                        "url" => $this->uploadDir . DIRECTORY_SEPARATOR . $fileName,
                        "name" => $fileName,
                        "size" => $fileSize
                    ]
                ]
            ];
            return $this->json($result);
        }

        $json = [
            "errorMessage" => "Pas d'image envoyer",
        ];

        return $this->json($json);
    }

    #[Route('/filemanager/gallery.json', name: 'admin.filemanager.json')]
    public function filemanagerGallery(AttachmentRepository $repository): Response
    {

        // response format: {
        //     "result": [
        //         {
        //             "src": "/download/editorImg/test_image.jpg", // @Require
        //             "thumbnail": "/download/editorImg/test_thumbnail.jpg", // @Option - Thumbnail image to be displayed in the image gallery.
        //             "name": "Test image", // @Option - default: src.split('/').pop()
        //             "alt": "Alt text", // @Option - default: src.split('/').pop()
        //             "tag": "Tag name" // @Option
        //         }
        //     ],
        //     "nullMessage": "Text string or HTML string", // It is displayed when "result" is empty.
        //     "errorMessage": "Insert error message", // It is displayed when an error occurs.
        // }

        $data = $repository->findAll();

        if ($data) {
            $result = [
                'statusCode' => 200,
                'result' => $data,
                'r2' => $data
            ];
        } else {
            $result = [
                "nullMessage" => "Text string or HTML string"
            ];
        }


        return $this->json($result, 200);
    }
}

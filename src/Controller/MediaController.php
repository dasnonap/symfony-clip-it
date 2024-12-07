<?php

namespace App\Controller;

use App\Entity\Media;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class MediaController extends AbstractController
{
    function __construct(
        public UploaderHelper $uploaderHelper,
    ) {}

    // #[Route('media/{id}', 'app_api_get_media', methods: ['GET'])]
    // function getMediaById(Media $media): BinaryFileResponse
    // {
    //     if (empty($media)) {
    //         throw new LogicException("Media is not found.");
    //     }

    //     $filePath = $this->uploaderHelper->asset($media, 'uploadFile');

    //     $response = new BinaryFileResponse(sprintf('%s/public/%s', $this->getParameter('kernel.project_dir'), $filePath));

    //     $response->setContentDisposition(
    //         ResponseHeaderBag::DISPOSITION_INLINE,
    //         $media->getUploadName(),
    //     );

    //     return $response;
    // }
}

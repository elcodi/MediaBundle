<?php

/**
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  ##author_placeholder
 * @version ##version_placeholder##
 */

namespace Elcodi\MediaBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Elcodi\MediaBundle\Exception\InvalidImageException;
use Elcodi\MediaBundle\Services\ImageManager;
use Elcodi\MediaBundle\Services\FileManager;

/**
 * Class ImageUploadController
 */
class ImageUploadController extends Controller
{
    /**
     * @var ObjectManager
     *
     * Entity manager
     */
    protected $objectManager;

    /**
     * @var ImageManager
     *
     * Image Manager
     */
    protected $imageManager;

    /**
     * @var FileManager
     *
     * File Manager
     */
    protected $fileManager;

    /**
     * @var string
     *
     * Field name when uploading
     */
    protected $uploadFieldName;

    /**
     * Construct method
     *
     * @param ObjectManager $objectManager   Object Manager
     * @param FileManager   $fileManager     File Manager
     * @param ImageManager  $imageManager    Image Manager
     * @param string        $uploadFieldName Field name when uploading
     */
    public function __construct(
        ObjectManager $objectManager,
        FileManager $fileManager,
        ImageManager $imageManager,
        $uploadFieldName
    )
    {
        $this->objectManager = $objectManager;
        $this->fileManager = $fileManager;
        $this->imageManager = $imageManager;
        $this->uploadFieldName = $uploadFieldName;
    }

    /**
     * Dynamic upload action
     *
     * @param Request $request Current request
     *
     * @return Response
     */
    public function uploadAction(Request $request)
    {
        /**
         * @var $file UploadedFile
         */
        $file = $request->files->get($this->uploadFieldName);

        try {
            $image = $this->imageManager->createImage($file);

        } catch (InvalidImageException $exception) {
            return new JsonResponse([
                'status' => 'ko',
            ]);
        }

        $this->objectManager->persist($image);
        $this->objectManager->flush($image);

        $this->fileManager->uploadFile(
            $image,
            file_get_contents($file->getRealPath()),
            true
        );

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }
}

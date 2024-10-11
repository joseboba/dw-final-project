<?php

namespace App\Service;

use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

class CloudinaryService
{
    public function __construct() {
    }

    public function upload(string $pathName): ApiResponse
    {
        Configuration::instance('cloudinary://654779369772558:j8DHoka7w8G0mY-rPFrMMeCv0qg@dwcmzrede?secure=true');
        $upload = new UploadApi();
        return $upload
            ->upload(
                $pathName,
                [
                    'folder' => 'employee',
                    'use_filename' => true,
                    'overwrite' => true,
                ]
            );
    }

    public function destroy(string $publicId): ApiResponse
    {
        Configuration::instance('cloudinary://654779369772558:j8DHoka7w8G0mY-rPFrMMeCv0qg@dwcmzrede?secure=true');
        $upload = new UploadApi();
        return $upload
            ->destroy($publicId);
    }
}
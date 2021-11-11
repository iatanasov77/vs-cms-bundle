<?php namespace VS\CmsBundle\Component\Generator;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use VS\CmsBundle\Model\ImageInterface;

final class UploadedImagePathGenerator implements ImagePathGeneratorInterface
{
    public function generate( ImageInterface $image ): string
    {
        /** @var UploadedFile $file */
        $file   = $image->getFile();
        
        $hash   = bin2hex( random_bytes( 16 ) );

        return $this->expandPath( $hash . '.' . $file->guessExtension() );
    }

    private function expandPath(string $path): string
    {
        return sprintf( '%s/%s/%s', substr($path, 0, 2 ), substr( $path, 2, 2 ), substr( $path, 4 ) );
    }
}

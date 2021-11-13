<?php namespace VS\CmsBundle\Component\Generator;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use VS\CmsBundle\Model\FileInterface;

final class UploadedFilePathGenerator implements FilePathGeneratorInterface
{
    public function generate( FileInterface $file ): string
    {
        /** @var UploadedFile $uploadedfile */
        $uploadedfile   = $file->getFile();
        
        $hash   = bin2hex( random_bytes( 16 ) );

        return $this->expandPath( $hash . '.' . $uploadedfile->guessExtension() );
    }

    private function expandPath( string $path ): string
    {
        return sprintf( '%s/%s/%s', substr( $path, 0, 2 ), substr( $path, 2, 2 ), substr( $path, 4 ) );
    }
}

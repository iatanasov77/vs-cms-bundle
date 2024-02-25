<?php namespace Vankosoft\CmsBundle\Component\Uploader;

use Gaufrette\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Webmozart\Assert\Assert;

use Vankosoft\CmsBundle\Component\Generator\FilePathGeneratorInterface;
use Vankosoft\CmsBundle\Component\Generator\UploadedFilePathGenerator;
use Vankosoft\CmsBundle\Model\Interfaces\FileInterface;

class ProfileUploader implements FileUploaderInterface
{
    /** @var Filesystem */
    protected $filesystem;

    /** @var FilePathGeneratorInterface */
    protected $filePathGenerator;

    public function __construct(
        Filesystem $filesystem,
        ?FilePathGeneratorInterface $filePathGenerator = null
    ) {
        $this->filesystem = $filesystem;

        if ( $filePathGenerator === null ) {
            @trigger_error( sprintf(
                'Not passing an $filePathGenerator to %s constructor is deprecated since Sylius 1.6 and will be not possible in Sylius 2.0.',
                self::class
            ), \E_USER_DEPRECATED );
        }

        $this->filePathGenerator = $filePathGenerator ?? new UploadedFilePathGenerator();
    }

    public function upload( FileInterface $image ): void
    {
        if ( ! $image->hasFile() ) {
            return;
        }

        $file = $image->getFile();

        /** @var File $file */
        Assert::isInstanceOf( $file, File::class );

        if ( null !== $image->getPath() && $this->has( $image->getPath() ) ) {
            $this->remove( $image->getPath() );
        }

        do {
            $path = $this->filePathGenerator->generate( $image );
        } while ( $this->isAdBlockingProne( $path ) || $this->filesystem->has( $path ) );

        $image->setPath( $path );

        $this->filesystem->write(
            $image->getPath(),
            file_get_contents( $image->getFile()->getPathname() )
        );
    }

    public function remove( string $path ): bool
    {
        if ( $this->filesystem->has( $path ) ) {
            return $this->filesystem->delete( $path );
        }

        return false;
    }

    private function has( string $path ): bool
    {
        return $this->filesystem->has( $path );
    }

    /**
     * Will return true if the path is prone to be blocked by ad blockers
     */
    private function isAdBlockingProne( string $path ): bool
    {
        return strpos( $path, 'ad' ) !== false;
    }
}

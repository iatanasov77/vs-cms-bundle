<?php namespace VS\CmsBundle\Component\Uploader;

use Gaufrette\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Webmozart\Assert\Assert;

use VS\CmsBundle\Component\Generator\ImagePathGeneratorInterface;
use VS\CmsBundle\Component\Generator\UploadedImagePathGenerator;
use VS\CmsBundle\Model\ImageInterface;

class ImageUploader implements ImageUploaderInterface
{
    /** @var Filesystem */
    protected $filesystem;

    /** @var ImagePathGeneratorInterface */
    protected $imagePathGenerator;

    public function __construct(
        Filesystem $filesystem,
        ?ImagePathGeneratorInterface $imagePathGenerator = null
    ) {
        $this->filesystem = $filesystem;

        if ($imagePathGenerator === null) {
            @trigger_error(sprintf(
                'Not passing an $imagePathGenerator to %s constructor is deprecated since Sylius 1.6 and will be not possible in Sylius 2.0.',
                self::class
            ), \E_USER_DEPRECATED);
        }

        $this->imagePathGenerator = $imagePathGenerator ?? new UploadedImagePathGenerator();
    }

    public function upload(ImageInterface $image): void
    {
        if (!$image->hasFile()) {
            return;
        }

        $file = $image->getFile();

        /** @var File $file */
        Assert::isInstanceOf($file, File::class);

        if (null !== $image->getPath() && $this->has($image->getPath())) {
            $this->remove($image->getPath());
        }

        do {
            $path = $this->imagePathGenerator->generate($image);
        } while ($this->isAdBlockingProne($path) || $this->filesystem->has($path));

        $image->setPath($path);

        $this->filesystem->write(
            $image->getPath(),
            file_get_contents($image->getFile()->getPathname())
        );
    }

    public function remove(string $path): bool
    {
        if ($this->filesystem->has($path)) {
            return $this->filesystem->delete($path);
        }

        return false;
    }

    private function has(string $path): bool
    {
        return $this->filesystem->has($path);
    }

    /**
     * Will return true if the path is prone to be blocked by ad blockers
     */
    private function isAdBlockingProne(string $path): bool
    {
        return strpos($path, 'ad') !== false;
    }
}

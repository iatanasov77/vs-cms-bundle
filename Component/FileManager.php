<?php namespace Vankosoft\CmsBundle\Component;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Vankosoft\CmsBundle\Component\Uploader\FileUploaderInterface;
use Vankosoft\CmsBundle\Model\FileManagerFileInterface;

class FileManager
{    
    private FileUploaderInterface $fileUploader;
    
    public function __construct(
        FileUploaderInterface $fileUploader
    ) {
        $this->fileUploader = $fileUploader;
    }
    
    public function upload2GaufretteFilesystem( FileManagerFileInterface &$file, File $postFile )
    {
        $file->setOriginalName( $postFile->getClientOriginalName() );
        
        try {
            $uploadedFile   = new UploadedFile( $postFile->getRealPath(), $postFile->getBasename() );
            $file->setFile( $uploadedFile );
            
            $this->fileUploader->upload( $file );
        } catch ( FileException $e ) {
            // ... handle exception if something happens during file upload
        }
    }
    
    public function upload2ArtgrisFileManager( File $file, $targetDir ): string
    {
        $originalFilename   = pathinfo( $file->getClientOriginalName(), PATHINFO_FILENAME );
        // this is needed to safely include the file name as part of the URL
        //$safeFilename       = $slugger->slug( $originalFilename );    // Slugger is included in Symfony 5.0
        $safeFilename       = $originalFilename;
        $newFilename        = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        
        // Move the file to the directory where brochures are stored
        try {
            $file->move(
                $targetDir,
                $newFilename
            );
        } catch ( FileException $e ) {
            // ... handle exception if something happens during file upload
        }
        
        return $newFilename;
    }
}

<?php namespace Vankosoft\CmsBundle\Component\OneupUploader;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Persistence\ManagerRegistry;
use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Response\ResponseInterface;
use Oneup\UploaderBundle\Event\PostPersistEvent;

class PostPersistListener
{
    /** @var ManagerRegistry */
    private ManagerRegistry $doctrine;
    
    public function __construct( ManagerRegistry $doctrine )
    {
        $this->doctrine = $doctrine;
    }
    
    public function onUpload( PostPersistEvent $event ): ResponseInterface
    {
        /** @var Request */
        $request    = $event->getRequest();
        
        /** @var ResponseInterface */
        $response   = $event->getResponse();
        
        $postData   = $request->request->all();
        if ( ! isset( $postData['fileResourceId'] ) ) {
            $response['DebugRequest']   = $postData;
            
            return $response;
        }
        $entityClass    = $postData['fileResourceClass'];
        
        /** @var FileInterface|File */
        $file           = $event->getFile();
        $uploadedFile   = $request()->files->get( 'file' );
        if ( isset( $postData['formName'] ) ) {
            $formFiles      = $request()->files->get( $postData['formName'] );
            if ( ! $formFiles ) {
                $response['error']  = 'Form Has Not Files !!!';
                return $response;
            }
            $uploadedFile   = $formFiles[$postData['fileInputFieldName']];
        }
        
        if ( intval( $postData['fileResourceId'] ) ) {
            $response['HasEntity']  = true;
            $entity = $this->doctrine->getRepository( $entityClass )->find( intval( $postData['fileResourceId'] ) );
            // @TODO Need Old File To Be Removed
        } else {
            $response['HasEntity']  = false;
            $entity = new $entityClass();
        }
        
        if ( \method_exists( $file, 'getFilesystem' ) ) {
            $entity->setType( $file->getFilesystem()->mimeType( $file->getPathname() ) );
        }
        $entity->setPath( $file->getPathname() );
        $entity->setOriginalName( $uploadedFile->getClientOriginalName() );
        
        $this->doctrine->getManager()->persist( $entity );
        $this->doctrine->getManager()->flush();
        
        $response['success']        = true;
        $response['resourceKey']    = $postData['fileResourceKey'];
        $response['resourceId']     = $entity->getId();
        
        return $response;
    }
}

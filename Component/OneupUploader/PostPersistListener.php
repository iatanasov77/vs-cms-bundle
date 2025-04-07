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
        
        $request    = $request->request->all();
        if ( ! isset( $request['fileResourceId'] ) ) {
            $response['DebugRequest']   = $request;
            
            return $response;
        }
        $entityClass    = $request['fileResourceClass'];
        
        /** @var FileInterface|File */
        $file           = $event->getFile();
        $uploadedFile   = $event->getRequest()->files->get( 'file' );
        if ( isset( $request['formName'] ) ) {
            $formFiles      = $event->getRequest()->files->get( $request['formName'] );
            if ( ! $formFiles ) {
                $response['error']  = 'Form Has Not Files !!!';
                return $response;
            }
            $uploadedFile   = $formFiles[$request['fileInputFieldName']];
        }
        
        if ( intval( $request['fileResourceId'] ) ) {
            $response['HasEntity']  = true;
            $entity = $this->doctrine->getRepository( $entityClass )->find( intval( $request['fileResourceId'] ) );
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
        $response['resourceKey']    = $request['fileResourceKey'];
        $response['resourceId']     = $entity->getId();
        
        return $response;
    }
}

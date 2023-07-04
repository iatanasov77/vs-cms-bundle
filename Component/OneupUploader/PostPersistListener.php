<?php namespace Vankosoft\CmsBundle\Component\OneupUploader;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Persistence\ManagerRegistry;
use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Response\ResponseInterface;
use Oneup\UploaderBundle\Event\PostPersistEvent;

class PostPersistListener
{
    /**
     * @var ManagerRegistry
     */
    private ManagerRegistry $doctrine;
    
    public function __construct( ManagerRegistry $doctrine )
    {
        $this->doctrine = $doctrine;
    }
    
    public function onUpload( PostPersistEvent $event )
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
            $uploadedFile   = $event->getRequest()->files->get( $request['formName'] )[$request['fileInputFieldName']];
        }
        
        if ( intval( $request['fileResourceId'] ) ) {
            $response['HasEntity']  = true;
            $entity = $this->doctrine->getRepository( $entityClass )->find( intval( $request['fileResourceId'] ) );
        } else {
            $response['HasEntity']  = false;
            $entity = new $entityClass();
        }
        
        if ( ! ( $file instanceof \Symfony\Component\HttpFoundation\File\File ) ) {
            $entity->setType( $file->getFilesystem()->mimeType( $file->getPathname() ) );
        }
        $entity->setPath( $file->getPathname() );
        $entity->setOriginalName( $uploadedFile->getClientOriginalName() );
        
        $this->doctrine->getManager()->persist( $entity );
        $this->doctrine->getManager()->flush();
        
        $response['success']    = true;
        $response['resources']  = [
            $request['fileResourceKey']  => $entity->getId()
        ];
        
/* https://github.com/1up-lab/OneupUploaderBundle/blob/master/doc/response.md
        $response->setSuccess( false );
        $response->setError( $msg );
        
        $response['GaufretteFilesystemPath']    = $file->getPathname();
        $response['GaufretteFileBasename']      = $file->getBasename();
        $response['OriginalName']               = $uploadedFile->getClientOriginalName();
        
        //$response['MimeType']               = $file->getMimeType();
        $response['MimeType']               = $file->getFilesystem()->mimeType( $file->getPathname() );
*/
        
        return $response;
    }
}

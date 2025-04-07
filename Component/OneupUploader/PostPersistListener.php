<?php namespace Vankosoft\CmsBundle\Component\OneupUploader;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Persistence\ManagerRegistry;
use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Response\ResponseInterface;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Vankosoft\ApplicationBundle\Component\ProjectIssue\ProjectIssue;

class PostPersistListener
{
    /** @var ManagerRegistry */
    private $doctrine;
    
    /** @var ProjectIssue */
    private $vsProject;
    
    public function __construct( ManagerRegistry $doctrine, ProjectIssue $vsProject )
    {
        $this->doctrine     = $doctrine;
        $this->vsProject    = $vsProject;
    }
    
    public function onUpload( PostPersistEvent $event ): ResponseInterface
    {
        /** @var Request */
        $request    = $event->getRequest();
        
        /** @var ResponseInterface */
        $response   = $event->getResponse();
        
        /** @var FileInterface | File */
        $file       = $event->getFile();
        
        /** @var array */
        $postData   = $request->request->all();
        
        if ( isset( $postData['requestType'] ) && $postData['requestType'] == 'VankosoftApi_TaskAtachment' ) {
            return $this->createVankosoftApiResource( $request, $response, $file, $postData );
        } else {
            return $this->createLocalResource( $request, $response, $file, $postData );
        }
    }
    
    private function createLocalResource(
        Request $request,
        ResponseInterface $response,
        FileInterface | File $file,
        array $postData
    ): ResponseInterface {
        
        if ( ! isset( $postData['fileResourceId'] ) ) {
            $response['DebugRequest']   = $postData;
            
            return $response;
        }
        $entityClass    = $postData['fileResourceClass'];
        
        $uploadedFile   = $request->files->get( 'file' );
        if ( isset( $postData['formName'] ) ) {
            $formFiles      = $request->files->get( $postData['formName'] );
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
    
    private function createVankosoftApiResource(
        Request $request,
        ResponseInterface $response,
        FileInterface | File $file,
        array $postData
    ): ResponseInterface {
        
        return $response;
    }
}

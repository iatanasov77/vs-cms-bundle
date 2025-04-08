<?php namespace Vankosoft\CmsBundle\Component\OneupUploader;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Response\ResponseInterface;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Doctrine\Persistence\ManagerRegistry;
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
        
        $uploadedFile   = $request->files->get( 'file' );
        if ( isset( $postData['formName'] ) ) {
            $formFiles      = $request->files->get( $postData['formName'] );
            if ( ! $formFiles ) {
                $response['error']  = 'Form Has Not Files !!!';
                return $response;
            }
            $uploadedFile   = $formFiles[$postData['fileInputFieldName']];
        }
        
        /** @var array */
        $postData   = $request->request->all();
        
        if (
            isset( $postData['requestType'] ) &&
            $postData['requestType'] == OneupUploaderRquest::REQUEST_TYPE_VANKOSOFT_API
        ) {
            return $this->createVankosoftApiResource( $response, $file, $uploadedFile, $postData );
        } else {
            return $this->createLocalResource( $response, $file, $uploadedFile, $postData );
        }
    }
    
    private function createLocalResource(
        ResponseInterface $response,
        FileInterface | File $file,
        UploadedFile $uploadedFile,
        array $postData
    ): ResponseInterface {
        
        if ( ! isset( $postData['fileResourceId'] ) ) {
            $response['DebugRequest']   = $postData;
            
            return $response;
        }
        $entityClass    = $postData['fileResourceClass'];
        
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
        ResponseInterface $response,
        FileInterface | File $file,
        UploadedFile $uploadedFile,
        array $postData
    ): ResponseInterface {
        
        switch ( $postData['requestTarget'] ) {
            case OneupUploaderRquest::REQUEST_TARGET_VANKOSOFT_API_TASK_ATTACHMENT:
                $this->vsProject->createKanbanboardTaskAttachment([
                    'taskId'                    => $postData['fileOwnerId'],
                    'attachmentId'              => $postData['fileResourceId'],
                    'attachmentPath'            => $file->getPathname(),
                    'attachmentOriginalName'    => $uploadedFile->getClientOriginalName(),
                    'attachmentFileType'        => \method_exists( $file, 'getFilesystem' ) ?
                                                    $file->getFilesystem()->mimeType( $file->getPathname() ) :
                                                    '',
                ]);
                
                break;
            default:
                throw new OneupUploaderException( 'Undefined Vankosoft API Request Target !!!' );
        }
        
        $response['success']        = true;
        $response['resourceKey']    = $postData['fileResourceKey'];
        
        return $response;
    }
}

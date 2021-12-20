<?php  namespace Vankosoft\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;

use Vankosoft\ApplicationBundle\Component\Status;
use Vankosoft\CmsBundle\Form\VankosoftFileManagerFileForm;
use Vankosoft\CmsBundle\Component\FileManager;

class VankosoftFileManagerExtController extends AbstractController
{
    /** @var EntityRepository */
    protected EntityRepository $fileManagerRepository;
    
    /** @var EntityRepository */
    protected EntityRepository $fileManagerFileRepository;
    
    /** @var Factory */
    protected Factory $fileManagerFileFactory;
    
    /** @var FileManager */
    protected FileManager $filemanager;
    
    public function __construct(
        EntityRepository $fileManagerRepository,
        EntityRepository $fileManagerFileRepository,
        Factory $fileManagerFileFactory,
        FileManager $filemanager
    ) {
        $this->fileManagerRepository        = $fileManagerRepository;
        $this->fileManagerFileRepository    = $fileManagerFileRepository;
        $this->fileManagerFileFactory       = $fileManagerFileFactory;
        $this->filemanager                  = $filemanager;
    }
    
    public function getFileUploadForm( $fileManagerId, $fileManagerFileId, Request $request ) : Response
    {
        if ( $fileManagerFileId ) {
            $fileEntity = $this->fileManagerFileRepository->find( $fileManagerFileId );
        } else {
            $fileEntity = $this->fileManagerFileFactory->createNew();
            $fileManager    = $this->fileManagerRepository->find( $fileManagerId );
            $fileEntity->setOwner( $fileManager );
        }
        
        return $this->render( '@VSCms/Pages/VankosoftFileManager/Partial/form_filemanager_upload_file.html.twig', [
            'form'          => $this->createForm( VankosoftFileManagerFileForm::class, $fileEntity )->createView(),
            'fileManagerId' => $fileManagerId,
        ]);
    }
    
    public function handleFileUploadForm( Request $request ) : JsonResponse
    {
        $fileEntity = $this->fileManagerFileFactory->createNew();
        $form       = $this->createForm( VankosoftFileManagerFileForm::class, $fileEntity );
        
        $form->handleRequest( $request );
        if ( $form->isSubmitted() && $form->isValid() ) {
            $em             = $this->getDoctrine()->getManager();
            $postFile       = $form['file']->getData();
            $fileManager    = $this->fileManagerRepository->find( $form['fileManagerId']->getData() );
            $fileEntity     = $form->getData();
            
            $this->filemanager->upload2GaufretteFilesystem( $fileEntity, $postFile );
            $fileManager->addFile( $fileEntity );
            
            $em->persist( $fileEntity );
            $em->flush();
            
            return new JsonResponse([
                'status'   => Status::STATUS_OK
            ]);
        }
        
        return new JsonResponse([
            'status'   => Status::STATUS_ERROR
        ]);
    }
}

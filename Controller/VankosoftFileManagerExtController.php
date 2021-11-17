<?php  namespace VS\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;

use VS\ApplicationBundle\Component\Status;
use VS\CmsBundle\Form\VankosoftFileManagerFileForm;
use VS\CmsBundle\Component\FileManager;

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
            'form'  => $this->createForm( VankosoftFileManagerFileForm::class, $fileEntity )->createView(),
        ]);
    }
    
    public function handleFileUploadForm( Request $request ) : JsonResponse
    {
        $fileEntity = $this->fileManagerFileFactory->createNew();
        $form       = $this->createForm( VankosoftFileManagerFileForm::class, $fileEntity );
        
        $form->handleRequest( $request );
        if ( $form->isSubmitted() && $form->isValid() ) {
            $file       = $form['file']->getData();
            var_dump( $file ); die;
            // $form->getData() holds the submitted values
            // but, the original `$fileEntity` variable has also been updated
            $fileEntity = $form->getData();
            $em         = $this->getDoctrine()->getManager();
            
            // ... perform some action, such as saving the fileEntity to the database
            $fileEntity->setFile( $uploadedImage );
            
            $this->uploader->upload( $fileEntity );
            
            $userInfo->setAvatar( $avatarImage );
            
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

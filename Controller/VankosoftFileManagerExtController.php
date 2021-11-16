<?php  namespace VS\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;

use VS\CmsBundle\Form\VankosoftFileManagerFileForm;

class VankosoftFileManagerExtController extends AbstractController
{
    /** @var EntityRepository */
    protected EntityRepository $fileManagerRepository;
    
    /** @var EntityRepository */
    protected EntityRepository $fileManagerFileRepository;
    
    /** @var Factory */
    protected Factory $fileManagerFileFactory;
    
    public function __construct(
        EntityRepository $fileManagerRepository,
        EntityRepository $fileManagerFileRepository,
        Factory $fileManagerFileFactory
    ) {
        $this->fileManagerRepository        = $fileManagerRepository;
        $this->fileManagerFileRepository    = $fileManagerFileRepository;
        $this->fileManagerFileFactory       = $fileManagerFileFactory;
    }
    
    public function getFileUploadForm( $fileManagerId, $fileManagerFileId, Request $request ) : Response
    {
        $fileEntity = $fileManagerId ?
                        $this->fileManagerFileRepository->find( $fileManagerFileId ) :
                        $this->fileManagerFileFactory->createNew();
        
        return $this->render( '@VSCms/Pages/VankosoftFileManager/Partial/form_filemanager_upload_file.html.twig', [
            'form'  => $this->createForm( VankosoftFileManagerFileForm::class, $fileEntity )->createView(),
        ]);
    }
    
    public function handleFileUploadForm( Request $request ) : Response
    {
        $em         = $this->get( 'doctrine.orm.entity_manager' );
    }
}

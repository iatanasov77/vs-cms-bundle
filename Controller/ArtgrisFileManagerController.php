<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Vankosoft\CmsBundle\Component\FileManager;
use Vankosoft\CmsBundle\Form\FileManager\UploadFileForm;

class ArtgrisFileManagerController extends AbstractController
{
    /** @var FileManager */
    protected FileManager $fm;
    
    public function __construct(
        FileManager $fm
    ) {
        $this->fm   = $fm;
    }
    
	/**
	 *	@TODO Try Implement This FileManager: https://ckeditor.com/docs/ckfinder/demo/ckfinder3/samples/ckeditor.html
	 */
    public function listFiles( Request $request ): Response
    {
        return $this->render( '@VSCms/Pages/ArtgrisFileManager/list_files.html.twig' );
    }
    
    public function uploadFile( Request $request ): Response
    {
        $form       = $this->createForm( UploadFileForm::class, null, [
            'action'    => $this->generateUrl( 'vs_cms_filemanager_artgris_upload' ),
            'method'    => 'POST',
        ]);
        
        $form->handleRequest( $request );
        if ( $form->isSubmitted() ) {
            $directory   = $form['directory']->getData();
            $file       = $form['file']->getData();
            if ( $file ) {
                $targetDir  = $this->getParameter( 'vs_cms.filemanager_shared_media_artgris' ) . $directory;
                $fileName   = $this->fm->upload2ArtgrisFileManager( $file, $targetDir );
            }
            
            return $this->redirectToRoute( 'vs_cms_filemanager_artgris_list' );
        }
        
        return $this->render( '@VSCms/Pages/ArtgrisFileManager/upload_file.html.twig', [
            'errors'        => $form->getErrors( true, false ),
            'form'          => $form->createView(),
        ]);
    }
    
    public function fosckeditorBrowse( $directory, Request $request ): Response
    {
        $path       = $path = $request->attributes->all();
        $query      = $request->query->all();
        
        return $this->forward( '\Artgris\Bundle\FileManagerBundle\Controller\ManagerController::indexAction', $path, $query );
    }
    
    public function fosckeditorUpload( $directory, Request $request ): Response
    {
        $path       = $path = $request->attributes->all();
        $query      = $request->query->all();
        
        return $this->forward( '\Artgris\Bundle\FileManagerBundle\Controller\ManagerController::uploadFileAction', $path, $query );
    }
}

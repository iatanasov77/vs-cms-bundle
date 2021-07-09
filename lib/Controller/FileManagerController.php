<?php namespace VS\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use VS\CmsBundle\Form\FileManager\UploadFileForm;

class FileManagerController extends AbstractController
{
    public function uploadFile( Request $request ): Response
    {
        $form       = $this->createForm( UploadFileForm::class, null, [
            'action'    => $this->generateUrl( 'vs_cms_filemanager_upload' ),
            'method'    => 'POST',
        ]);
        
        $form->handleRequest( $request );
        if ( $form->isSubmitted() ) {
            $formData   = $form->getData();
            
            if ( $formData['file'] ) {
                $targetDir  = $this->getParameter( 'kernel.project_dir' ) . $formData['directory'];
                $fileName   = $this->handleFileUpload( $formData['file'], $targetDir );
            }

            return $this->redirectToRoute( 'file_manager', ['conf' => 'default'] );
        }
        
        return $this->render( '@VSCms/Pages/FileManager/upload_file.html.twig', [
            'errors'        => $form->getErrors( true, false ),
            'form'          => $form->createView(),
        ]);
    }
    
    public function fosckeditorBrowse( $directory, Request $request )
    {
        $response = $this->forward( 'Artgris\Bundle\FileManagerBundle\Controller::indexAction' );
        
        return $response;
    }
    
    public function fosckeditorUpload( $directory, Request $request )
    {
        $response = $this->forward( 'Artgris\Bundle\FileManagerBundle\Controller::uploadFileAction' );
        
        return $response;
    }
    
    protected function handleFileUpload( $file, $targetDir ) : string
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

<?php  namespace Vankosoft\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Liip\ImagineBundle\Binary\Loader\LoaderInterface;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;

class GetImageController extends AbstractController
{
    /* LoaderInterface */
    private $imagineLoader;
    
    /* FilterManager */
    private $imagineFilterManager;
    
    public function __construct( LoaderInterface $imagineLoader, FilterManager $imagineFilterManager )
    {
        $this->imagineLoader         = $imagineLoader;
        $this->imagineFilterManager  = $imagineFilterManager;
    }
    
    public function getFile( $file, Request $request ): Response
    {
        $filter = $request->query->get( 'filter' );
        
        if ( $filter ) {
            $filteredImageBinary = $this->imagineFilterManager->applyFilter( $this->imagineLoader->find( $file ), $filter );
            
            return new Response( $filteredImageBinary->getContent(), 200, [
                'Content-Type' => $filteredImageBinary->getMimeType(),
            ]);
        } else {
            return new BinaryFileResponse( $this->getParameter( 'kernel.project_dir' ) . '/' . $file );
        }
    }
    
    public function getFilesystemFile( $file, Request $request ): Response
    {
        return new BinaryFileResponse( $file );
    }
}

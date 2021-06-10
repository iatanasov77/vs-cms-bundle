<?php  namespace VS\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GetImageController extends AbstractController
{
    public function getFile( $file, Request $request )
    {
        $filter = $request->query->get( 'filter' );
        
        if ( $filter ) {
            /** @var \Liip\ImagineBundle\Binary\Loader\FileSystemLoader $loader */
            //$loader = $this->get( 'liip_imagine.binary.loader.filesystem' );
            $loader = $this->get( 'liip_imagine.binary.loader.default' );
            
            /** @var \Liip\ImagineBundle\Imagine\Filter\FilterManager $filterManager */
            $filterManager = $this->get( 'liip_imagine.filter.manager' );
            
            $filteredImageBinary = $filterManager->applyFilter( $loader->find( $file ), $filter );
            
            return new Response( $filteredImageBinary->getContent(), 200, [
                'Content-Type' => $filteredImageBinary->getMimeType(),
            ]);
        } else {
            return new BinaryFileResponse( $this->getParameter( 'kernel.project_dir' ) . '/' . $file );
        }
    }
}

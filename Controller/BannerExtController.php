<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Vankosoft\CmsBundle\Repository\BannerPlaceRepository;
use Vankosoft\CmsBundle\Repository\BannerRepository;
use Vankosoft\CmsBundle\Form\BannerForm;
use Vankosoft\CmsBundle\Component\FileManager;
use Vankosoft\ApplicationBundle\Component\Status;

class BannerExtController extends AbstractController
{
    /** @var ManagerRegistry */
    protected $doctrine;
    
    /** @var BannerPlaceRepository */
    protected $bannerPlaceRepository;
    
    /** @var BannerRepository */
    protected $bannerRepository;
    
    /** @var FactoryInterface */
    protected $bannerFactory;
    
    /** @var FileManager */
    protected $fileManager;
    
    public function __construct(
        ManagerRegistry $doctrine,
        BannerPlaceRepository $bannerPlaceRepository,
        BannerRepository $bannerRepository,
        FactoryInterface $bannerFactory,
        FileManager $fileManager
    ) {
        $this->doctrine                 = $doctrine;
        $this->bannerPlaceRepository    = $bannerPlaceRepository;
        $this->bannerRepository         = $bannerRepository;
        $this->bannerFactory            = $bannerFactory;
        $this->fileManager              = $fileManager;
    }
    
    public function sortAction( $id, $insertAfterId, Request $request ): Response
    {
        $em             = $this->doctrine->getManager();
        $item           = $this->bannerRepository->find( $id );
        $insertAfter    = $this->bannerRepository->find( $insertAfterId );
        $this->bannerRepository->insertAfter( $item, $insertAfterId );

        $position       = $insertAfter ? ( $insertAfter->getPosition() + 1 ) : 1;
        $item->setPosition( $position );
        $em->persist( $item );
        $em->flush();
        
        return new JsonResponse([
            'status'   => Status::STATUS_OK
        ]);
    }
    
    public function editBanner( $placeId, $itemId, $locale, Request $request ): Response
    {
        $place  = $this->bannerPlaceRepository->find( $placeId );
        $em     = $this->doctrine->getManager();
        
        $itemId     = intval( $itemId );
        $banner     = $itemId ? $this->bannerRepository->find( $itemId ) : $this->bannerFactory->createNew();
        $formAction = $itemId ? 
                            $this->generateUrl( 'vs_cms_banner_update', ['placeId' => $placeId, 'id' => $itemId] ) :
                            $this->generateUrl( 'vs_cms_banner_create', ['placeId' => $placeId] );
        $formMethod     = $itemId ? 'PUT' : 'POST';
        
        if ( $locale != $request->getLocale() ) {
            $banner->setTranslatableLocale( $locale );
            $em->refresh( $banner );
        }
        
        $form   = $this->createForm( BannerForm::class, $banner, [
            'action'        => $formAction,
            'method'        => $formMethod,
            'data'          => $banner,
            'bannerPlace'   => $place,
        ]);
        
        return $this->render( '@VSCms/Pages/Banners/banner_form.html.twig', [
            'form'      => $form->createView(),
            'placeId'   => $placeId,
            'item'      => $banner,
        ]);
    }
    
    public function deleteBanner( $placeId, $itemId, Request $request ): Response
    {
        $em      = $this->doctrine->getManager();
        $banner = $this->bannerRepository->find( $itemId );
        
        $em->remove( $banner );
        $em->flush();
        
        return $this->redirectToRoute( 'vs_cms_banner_place_update', ['id' => $placeId] );
    }
}
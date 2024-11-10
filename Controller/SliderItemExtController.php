<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Vankosoft\CmsBundle\Repository\SliderItemRepository;
use Vankosoft\CmsBundle\Form\SliderItemForm;
use Vankosoft\CmsBundle\Component\FileManager;
use Vankosoft\ApplicationBundle\Component\Status;

class SliderItemExtController extends AbstractController
{
    /** @var ManagerRegistry */
    protected $doctrine;
    
    /** @var RepositoryInterface */
    protected $sliderRepository;
    
    /** @var SliderItemRepository */
    protected $sliderItemRepository;
    
    /** @var FactoryInterface */
    protected $sliderItemFactory;
    
    /** @var FileManager */
    protected $fileManager;
    
    /** @var string */
    protected $sliderPhotoDescription;
    
    public function __construct(
        ManagerRegistry $doctrine,
        RepositoryInterface $sliderRepository,
        SliderItemRepository $sliderItemRepository,
        FactoryInterface $sliderItemFactory,
        FileManager $fileManager,
        string $sliderPhotoDescription
    ) {
        $this->doctrine                 = $doctrine;
        $this->sliderRepository         = $sliderRepository;
        $this->sliderItemRepository     = $sliderItemRepository;
        $this->sliderItemFactory        = $sliderItemFactory;
        $this->fileManager              = $fileManager;
        $this->sliderPhotoDescription   = $sliderPhotoDescription;
    }
    
    public function sortAction( $id, $insertAfterId, Request $request ): Response
    {
        $em             = $this->doctrine->getManager();
        $item           = $this->sliderItemRepository->find( $id );
        $insertAfter    = $this->sliderItemRepository->find( $insertAfterId );
        $this->sliderItemRepository->insertAfter( $item, $insertAfterId );

        $position       = $insertAfter ? ( $insertAfter->getPosition() + 1 ) : 1;
        $item->setPosition( $position );
        $em->persist( $item );
        $em->flush();
        
        return new JsonResponse([
            'status'   => Status::STATUS_OK
        ]);
    }
    
    public function editSliderItem( $sliderId, $itemId, $locale, Request $request ): Response
    {
        $slider = $this->sliderRepository->find( $sliderId );
        $em     = $this->doctrine->getManager();
        
        $itemId         = intval( $itemId );
        $sliderItem     = $itemId ? $this->sliderItemRepository->find( $itemId ) : $this->sliderItemFactory->createNew();
        $formAction     = $itemId ? 
                            $this->generateUrl( 'vs_cms_slider_item_update', ['sliderId' => $sliderId, 'id' => $itemId] ) :
                            $this->generateUrl( 'vs_cms_slider_item_create', ['sliderId' => $sliderId] );
        $formMethod     = $itemId ? 'PUT' : 'POST';
        
        if ( $locale != $request->getLocale() ) {
            $sliderItem->setTranslatableLocale( $locale );
            $em->refresh( $sliderItem );
        }
        
        $form   = $this->createForm( SliderItemForm::class, $sliderItem, [
            'action'                        => $formAction,
            'method'                        => $formMethod,
            'data'                          => $sliderItem,
            'slider'                        => $slider,
            
            'ckeditor_uiColor'              => $this->getParameter( 'vs_cms.form.decription_field.ckeditor_uiColor' ),
            'ckeditor_toolbar'              => $this->getParameter( 'vs_cms.form.decription_field.ckeditor_toolbar' ),
            'ckeditor_extraPlugins'         => $this->getParameter( 'vs_cms.form.decription_field.ckeditor_extraPlugins' ),
            'ckeditor_removeButtons'        => $this->getParameter( 'vs_cms.form.decription_field.ckeditor_removeButtons' ),
            'ckeditor_allowedContent'       => $this->getParameter( 'vs_cms.form.decription_field.ckeditor_allowedContent' ),
            'ckeditor_extraAllowedContent'  => $this->getParameter( 'vs_cms.form.decription_field.ckeditor_extraAllowedContent' ),
        ]);
        
        return $this->render( '@VSCms/Pages/SlidersItems/slider_item_form.html.twig', [
            'form'                      => $form->createView(),
            'sliderId'                  => $sliderId,
            'item'                      => $sliderItem,
            'sliderPhotoDescription'    => $this->sliderPhotoDescription,
        ]);
    }
    
    public function deleteSliderItem( $sliderId, $itemId, Request $request ): Response
    {
        $em         = $this->doctrine->getManager();
        $sliderItem = $this->sliderItemRepository->find( $itemId );
        
        $em->remove( $sliderItem );
        $em->flush();
        
        return $this->redirectToRoute( 'vs_cms_slider_update', ['id' => $sliderId] );
    }
}
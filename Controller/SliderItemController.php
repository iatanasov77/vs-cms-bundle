<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Vankosoft\ApplicationBundle\Controller\AbstractCrudController;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Resource\ResourceActions;
use Vankosoft\ApplicationBundle\Component\Status;

use Vankosoft\CmsBundle\Model\Interfaces\SliderItemInterface;

class SliderItemController extends AbstractCrudController
{
    public function deleteAction( Request $request ): Response
    {
        $configuration = $this->requestConfigurationFactory->create( $this->metadata, $request );
        $this->isGrantedOr403( $configuration, ResourceActions::DELETE );
        
        $resource   = $this->findOr404( $configuration );
        $em         = $this->get( 'doctrine' )->getManager();
        
        $this->removePhotoFile( $resource );
        $em->remove( $resource );
        $em->flush();
        
        $redirectUrl    = $request->request->get( 'redirectUrl' );
        if ( $redirectUrl ) {
            return $this->redirect( $redirectUrl );
        }
        
        return new JsonResponse([
            'status'   => Status::STATUS_OK
        ]);
    }
    
    protected function customData( Request $request, $entity = NULL ): array
    {
        $translations   = $this->classInfo['action'] == 'indexAction' ? $this->getTranslations() : [];
        
        return [
            'translations'              => $translations,
            'sliderPhotoDescription'    => $this->getParameter( 'vs_cms.form.slider_item.photo.description' ),
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $formPost   = $request->request->all( 'slider_item_form' );
        $formLocale = $formPost['locale'];
        
        if ( $formLocale ) {
            $entity->setTranslatableLocale( $formLocale );
        }
        
        $photoFile    = $form['photo']->getData();
        if ( $photoFile ) {
            $this->createPhoto( $entity, $photoFile );
        }
    }
    
    private function createPhoto( SliderItemInterface &$sliderItem, File $file ): void
    {
        $sliderItemPhoto = $sliderItem->getPhoto() ?: $this->get( 'vs_cms.factory.slider_item_photo' )->createNew();
        $sliderItemPhoto->setOriginalName( $file->getClientOriginalName() );
        $sliderItemPhoto->setSliderItem( $sliderItem );
        
        $uploadedFile   = new UploadedFile( $file->getRealPath(), $file->getBasename() );
        $sliderItemPhoto->setFile( $uploadedFile );
        $this->get( 'vs_cms.slider_uploader' )->upload( $sliderItemPhoto );
        
        // reset File Because: Serialization of 'Symfony\Component\HttpFoundation\File\UploadedFile' is not allowed
        $sliderItemPhoto->setFile( null );
        
        if ( ! $sliderItem->getPhoto() ) {
            $sliderItem->setPhoto( $sliderItemPhoto );
        }
    }
    
    private function removePhotoFile( SliderItemInterface $sliderItem )
    {
        $em                 = $this->get( 'doctrine' )->getManager();
        $sliderPhotoDir     = $this->getParameter( 'vs_cms.filemanager_shared_media_gaufrette.slider' );
        $sliderItemPhoto    = $sliderPhotoDir . '/' . $sliderItem->getPhoto()->getPath();
        
        $em->remove( $sliderItem->getPhoto() );
        $em->flush();
        
        $filesystem     = new Filesystem();
        $filesystem->remove( $sliderItemPhoto );
    }
}

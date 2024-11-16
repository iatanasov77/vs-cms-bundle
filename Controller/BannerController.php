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

use Vankosoft\CmsBundle\Model\Interfaces\BannerInterface;

class BannerController extends AbstractCrudController
{
    public function deleteAction( Request $request ): Response
    {
        $configuration = $this->requestConfigurationFactory->create( $this->metadata, $request );
        $this->isGrantedOr403( $configuration, ResourceActions::DELETE );
        
        $resource   = $this->findOr404( $configuration );
        $em         = $this->get( 'doctrine' )->getManager();
        
        $this->removeImageFile( $resource );
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
            'translations'      => $translations,
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $formPost   = $request->request->all( 'banner_form' );
        $formLocale = $formPost['locale'];
        
        if ( $formLocale ) {
            $entity->setTranslatableLocale( $formLocale );
        }
        
        $imageFile    = $form['image']->getData();
        if ( $imageFile ) {
            $this->createImage( $entity, $imageFile );
        }
    }
    
    private function createImage( BannerInterface &$banner, File $file ): void
    {
        $bannerImage = $banner->getImage() ?: $this->get( 'vs_cms.factory.banner_image' )->createNew();
        $bannerImage->setOriginalName( $file->getClientOriginalName() );
        $bannerImage->setBanner( $banner );
        
        $uploadedFile   = new UploadedFile( $file->getRealPath(), $file->getBasename() );
        $bannerImage->setFile( $uploadedFile );
        $this->get( 'vs_cms.slider_uploader' )->upload( $bannerImage );
        
        // reset File Because: Serialization of 'Symfony\Component\HttpFoundation\File\UploadedFile' is not allowed
        $bannerImage->setFile( null );
        
        if ( ! $banner->getImage() ) {
            $banner->setImage( $bannerImage );
        }
    }
    
    private function removeImageFile( BannerInterface $banner )
    {
        $em                 = $this->get( 'doctrine' )->getManager();
        $sliderPhotoDir     = $this->getParameter( 'vs_cms.filemanager_shared_media_gaufrette.slider' );
        $bannerImage        = $sliderPhotoDir . '/' . $banner->getImage()->getPath();
        
        $em->remove( $banner->getImage() );
        $em->flush();
        
        $filesystem     = new Filesystem();
        $filesystem->remove( $bannerImage );
    }
    
    private function getTranslations()
    {
        $translations   = [];
        $transRepo      = $this->get( 'vs_application.repository.translation' );
        
        foreach ( $this->getRepository()->findAll() as $banner ) {
            $translations[$banner->getId()] = array_keys( $transRepo->findTranslations( $banner ) );
        }
        //echo "<pre>"; var_dump($translations); die;
        return $translations;
    }
}

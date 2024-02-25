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

use App\Entity\Slider;

class SliderItemController extends AbstractCrudController
{
    use GlobalFormsTrait;
    
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
            'translations'      => $translations,
        ];
    }
    
    protected function prepareEntity( &$entity, &$form, Request $request )
    {
        $formPost   = $request->request->all( 'slider_form' );
        $formLocale = $formPost['locale'];
        
        if ( $formLocale ) {
            $entity->setTranslatableLocale( $formLocale );
        }
        
        $photoFile    = $form['photo']->getData();
        if ( $photoFile ) {
            $this->createPhoto( $entity, $photoFile );
        }
    }
    
    private function createPhoto( Slider &$slider, File $file ): void
    {
        $sliderPhoto = $slider->getPhoto() ?: $this->get( 'vs_vvp.factory.slider_photo' )->createNew();
        $sliderPhoto->setOriginalName( $file->getClientOriginalName() );
        $sliderPhoto->setSlider( $slider );
        
        $uploadedFile   = new UploadedFile( $file->getRealPath(), $file->getBasename() );
        $sliderPhoto->setFile( $uploadedFile );
        $this->get( 'vs_vvp.slider_uploader' )->upload( $sliderPhoto );
        $sliderPhoto->setFile( null ); // reset File Because: Serialization of 'Symfony\Component\HttpFoundation\File\UploadedFile' is not allowed
        
        if ( ! $slider->getPhoto() ) {
            $slider->setPhoto( $sliderPhoto );
        }
    }
    
    private function removePhotoFile( Slider $slider )
    {
        $em             = $this->get( 'doctrine' )->getManager();
        $sliderPhoto    = $this->getParameter( 'vs_vvp.slider_directory' ) . '/' . $slider->getPhoto()->getPath();
        
        $em->remove( $slider->getPhoto() );
        $em->flush();
        
        $filesystem     = new Filesystem();
        $filesystem->remove( $sliderPhoto );
    }
    
    private function getTranslations()
    {
        $translations   = [];
        $transRepo      = $this->get( 'vs_application.repository.translation' );
        
        foreach ( $this->getRepository()->findAll() as $slider ) {
            $translations[$slider->getId()] = array_keys( $transRepo->findTranslations( $slider ) );
        }
        //echo "<pre>"; var_dump($translations); die;
        return $translations;
    }
}

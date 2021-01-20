<?php namespace VS\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use VS\CmsBundle\Model\PageCategory;

use FOS\RestBundle\View\View;
use Sylius\Component\Resource\ResourceActions;

class PagesController extends ResourceController
{
    public function indexAction( Request $request ) : Response
    {
        $configuration = $this->requestConfigurationFactory->create( $this->metadata, $request );
        
        $this->isGrantedOr403( $configuration, ResourceActions::INDEX );
        $resource = $this->findOr404( $configuration );
        
        $view = View::create( $resource );
        //var_dump( $view ); die;
        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate( $configuration->getTemplate( ResourceActions::INDEX . '.html' ) )
                ->setTemplateVar( $this->metadata->getName() )
                ->setData([
                    'configuration'             => $configuration,
                    'metadata'                  => $this->metadata,
                    'resource'                  => $resource,
                    $this->metadata->getName()  => $resource,
                    'items'                     => $this->getPagesRepository()->findAll(),
                ])
            ;
        }
        
        return $this->viewHandler->handle( $configuration, $view );
    }
    
    public function createAction( Request $request ) : Response
    {
        return $this->editAction( 0, $request );
    }
    
    public function updateAction( Request $request ) : Response
    {
        return $this->editAction( $request->attributes->get( 'id' ), $request );
    }
    
    public function editAction( $id, Request $request )
    {
        $configuration  = $this->requestConfigurationFactory->create( $this->metadata, $request );
        
        $er             = $this->getPagesRepository();
        $oPage          = $id ? $er->findOneBy( ['id' => $id] ) : $this->getPagesFactory()->createNew();
        $form           = $this->resourceFormFactory->create( $configuration, $oPage );
        
        if ( in_array( $request->getMethod(), ['POST', 'PUT', 'PATCH'], true ) && $form->handleRequest( $request) ) { // ->isValid()
            $em     = $this->getDoctrine()->getManager();
            $entity = $form->getData();
            $post   = $request->request->get( 'page_form' );
            
            $entity->getCategory()->setTaxon( $this->getTaxon( $post['category_taxon'] ) );
            $entity->setTranslatableLocale( $form['locale']->getData() );
            
            $em->persist( $entity );
            $em->flush();
            
            if ( $form->getClickedButton() && 'btnApply' === $form->getClickedButton()->getName() ) {
                return $this->redirect( $this->generateUrl( 'vs_cms_pages_update', ['id' => $entity->getId()] ) );
            } else {
                return $this->redirect( $this->generateUrl( 'vs_cms_pages_index' ) );
            }
        }
        
        return $this->render( '@VSCms/Pages/update.html.twig', [
            'form'          => $form->createView(),
            'item'          => $oPage,
            'taxonomyId'    => \App\Entity\Cms\PageCategory::TAXONOMY_ID
        ]);
    }
    
    protected function getPagesRepository()
    {
        return $this->get( 'vs_cms.repository.pages' );
    }
    
    protected function getPagesFactory()
    {
        return $this->get( 'vs_cms.factory.pages' );
    }
    
    protected function getTaxon( $taxonId )
    {
        return $this->get( 'vs_application.repository.taxon' )->find( $taxonId );
    }
}
    
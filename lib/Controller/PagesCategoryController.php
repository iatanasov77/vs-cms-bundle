<?php  namespace VS\CmsBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Documentation
 * --------------
 * http://atlantic18.github.io/DoctrineExtensions/doc/tree.html
 *
 * Good example
 * -------------
 * http://drib.tech/programming/hierarchical-data-relational-databases-symfony-4-doctrine
 * https://github.com/dribtech/hierarchical-data-tutorial-part-2
 */
class PagesCategoryController extends ResourceController
{
    public function indexAction( Request $request ): Response
    {
        $er = $this->getPageCategoryRepository();
        
        //$categories = $er->childrenHierarchy();
        $categories = $er->findAll();
        
        return $this->render( '@VSCms/Page_Categories/index.html.twig', [
            'items'         => $categories,
            //'countProjects' => $er->countTotal()
        ]);
    }
    
    public function createAction( Request $request ): Response
    {
        $configuration  = $this->requestConfigurationFactory->create( $this->metadata, $request );
        $oCategory      = $this->getPageCategoryFactory()->createNew();
        $form           = $this->resourceFormFactory->create( $configuration, $oCategory );
        
        if ( in_array( $request->getMethod(), ['POST', 'PUT', 'PATCH'], true ) && $form->handleRequest( $request)->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist( $form->getData() );
            $em->flush();
            
            return $this->redirect( $this->generateUrl( 'vs_cms_page_categories_index' ) );
        }
        
        return $this->render( '@VSCms/Page_Categories/edit.html.twig', [
            'form'          => $form->createView(),
            'item'          => $oCategory,
        ]);
    }
    
    public function updateAction( Request $request ) : Response
    {
        $configuration  = $this->requestConfigurationFactory->create( $this->metadata, $request );
        $oCategory      = $this->getPageCategoryRepository()->find( $request->attributes->get( 'id' ) );
        $form           = $this->resourceFormFactory->create( $configuration, $oCategory );
        
        if ( in_array( $request->getMethod(), ['POST', 'PUT', 'PATCH'], true ) && $form->handleRequest( $request)->isValid() ) {
            $em         = $this->getDoctrine()->getManager();
            $category   = $form->getData();
            $category->setTranslatableLocale( $form['locale']->getData() );
            
            $em->persist( $category );
            $em->flush();
            
            return $this->redirect( $this->generateUrl( 'vs_cms_page_categories_index' ) );
        }
        
        return $this->render( '@VSCms/Page_Categories/edit.html.twig', [
            'form'          => $form->createView(),
            'item'          => $oCategory,
        ]);
    }
    
    protected function getPageCategoryRepository()
    {
        return $this->get( 'vs_cms.repository.page_categories' );
    }
    
    protected function getPageCategoryFactory()
    {
        return $this->get( 'vs_cms.factory.page_categories' );
    }
}

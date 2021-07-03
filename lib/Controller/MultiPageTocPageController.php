<?php namespace VS\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sylius\Component\Resource\Repository\RepositoryInterface;

use VS\ApplicationBundle\Component\Slug;
use VS\ApplicationBundle\Form\TaxonForm;
use VS\ApplicationBundle\Repository\TaxonomyRepository;
use VS\ApplicationBundle\Repository\TaxonRepository;

class MultiPageTocPageController extends AbstractController
{
    public function editTocPage( RepositoryInterface $multipageTocRepository, $tocId, Request $request ): Response
    {
        $locale         = $request->getLocale();
        $tocRootPage    = $multipageTocRepository->find( $tocId )->getTocRootPage();
        
        $oTocPage       = $this->get( 'vs_application.factory.taxon' )->createNew();
        $oTocPage->setTranslatableLocale( $locale );
        
        $form           = $this->createForm( TaxonForm::class, $oTocPage, [
            'data'          => $oTocPage,
            'method'        => 'POST',
            'tocRootPage'   => $tocRootPage
        ]);
        
        return $this->render( '@VSCms/Pages/MultipageToc/form/toc_page.html.twig', [
            'form'          => $form->createView(),
        ]);
    }
    
    public function handleTocPage( RepositoryInterface $multipageTocRepository, TaxonRepository $taxonRepository, Request $request ): Response
    {
        $form   = $this->createForm( TaxonForm::class );
        
        if ( $request->isMethod( 'POST' ) ) {
            $parentTaxon    = $multipageTocRepository->find( $_POST['taxon_form']['parentTaxon'] );
            
            //$form->submit( $request->request->get( $form->getName() ) );
            
            if ( $form->isSubmitted()  ) { // && $form->isValid()
                $em         = $this->getDoctrine()->getManager();
                $oTocPage   = $form->getData();
                $oTocPage->setParent( $parentTaxon );
                
                $em->persist( $oTocPage );
                $em->flush();
                
                $tocId = $request->attributes->get( '$tocId' );
                return $this->redirect( $this->generateUrl( 'vs_application_taxonomy_update', ['id' => $tocId] ) );
            }
        }
        
        return new Response( 'The form is not submited properly !!!', 500 );
    }
    
    public function gtreeTableSource( RepositoryInterface $multipageTocRepository, $tocId, Request $request ): Response
    {   
        $parentId   = (int)$request->query->get( 'parentTaxonId' );
        
        return new JsonResponse( $this->gtreeTableData( $tocId, $parentId ) );
    }
    
    public function easyuiComboTreeSource( TaxonomyRepository $taxonomyRepository, TaxonRepository $taxonRepository, $tocId, Request $request ): Response
    {
        return new JsonResponse( $this->easyuiComboTreeData( $tocId ) );
    }
}

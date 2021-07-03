<?php namespace VS\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sylius\Component\Resource\Factory\FactoryInterface;

use VS\ApplicationBundle\Component\Slug;

//use Sylius\Component\Resource\Repository\RepositoryInterface;
use VS\CmsBundle\Repository\MultiPageTocRepository;
use VS\CmsBundle\Repository\TocPagesRepository;
use VS\CmsBundle\Form\TocPageForm;

class MultiPageTocPageController extends AbstractController
{
    private $tocRepository;
    private $tocPageRepository;
    private $tocPageFactory;
    
    public function __construct(
        MultiPageTocRepository $tocRepository,
        TocPagesRepository $tocPageRepository,
        FactoryInterface $tocPageFactory
    ) {
        $this->tocRepository        = $tocRepository;
        $this->tocPageRepository    = $tocPageRepository;
        $this->tocPageFactory       = $tocPageFactory;
    }
    
    public function editTocPage( $tocId, Request $request ): Response
    {
        $locale         = $request->getLocale();
        $tocRootPage    = $this->tocRepository->find( $tocId )->getTocRootPage();
        
        $oTocPage       = $this->tocPageFactory->createNew();
        //$oTocPage->setTranslatableLocale( $locale );
        
        $form           = $this->createForm( TocPageForm::class, $oTocPage, [
            'data'          => $oTocPage,
            'method'        => 'POST',
            'tocRootPage'   => $tocRootPage
        ]);
        
        return $this->render( '@VSCms/Pages/MultipageToc/form/toc_page.html.twig', [
            'form'          => $form->createView(),
        ]);
    }
    
    public function handleTocPage( Request $request ): Response
    {
        $form   = $this->createForm( TocPageForm::class );
        
        if ( $request->isMethod( 'POST' ) ) {
            //$parentTocPage    = $multipageTocRepository->find( $_POST['taxon_form']['parentTaxon'] );
            
            //$form->submit( $request->request->get( $form->getName() ) );
            
            if ( $form->isSubmitted()  ) { // && $form->isValid()
                $em         = $this->getDoctrine()->getManager();
                $oTocPage   = $form->getData();
                //$oTocPage->setParent( $parentTocPage );
                
                $em->persist( $oTocPage );
                $em->flush();
                
                $tocId = $request->attributes->get( '$tocId' );
                return $this->redirect( $this->generateUrl( 'vs_cms_multipage_toc_update', ['id' => $tocId] ) );
            }
        }
        
        return new Response( 'The form is not submited properly !!!', 500 );
    }
    
    public function gtreeTableSource( $tocId, Request $request ): Response
    {
        $parentId   = (int)$request->query->get( 'parentTaxonId' );
        
        return new JsonResponse( $this->gtreeTableData( $tocId, $parentId ) );
    }
    
    public function easyuiComboTreeSource( TaxonRepository $taxonRepository, $tocId, Request $request ): Response
    {
        return new JsonResponse( $this->easyuiComboTreeData( $tocId ) );
    }
}

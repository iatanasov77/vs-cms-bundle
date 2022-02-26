<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sylius\Component\Resource\Factory\FactoryInterface;

use Vankosoft\ApplicationBundle\Component\Slug;
use Vankosoft\ApplicationBundle\Repository\TaxonomyRepository;

use Vankosoft\CmsBundle\Repository\MultiPageTocRepository;
use Vankosoft\CmsBundle\Repository\TocPagesRepository;
use Vankosoft\CmsBundle\Repository\PagesRepository;
use Vankosoft\CmsBundle\Form\TocPageForm;
use Vankosoft\CmsBundle\Repository\DocumentsRepository;

class MultiPageTocPageController extends AbstractController
{
    /** @var DocumentsRepository */
    private $documentRepository;
    
    /** @var TocPagesRepository */
    private $tocPageRepository;
    
    /** @var FactoryInterface */
    private $tocPageFactory;
    
    /** @var PagesRepository */
    private $pagesRepository;
    
    /** @var TaxonomyRepository */
    private $taxonomyRepository;
    
    public function __construct(
        DocumentsRepository $documentRepository,
        TocPagesRepository $tocPageRepository,
        FactoryInterface $tocPageFactory,
        PagesRepository $pagesRepository,
        TaxonomyRepository $taxonomyRepository
    ) {
        $this->documentRepository   = $documentRepository;
        $this->tocPageRepository    = $tocPageRepository;
        $this->tocPageFactory       = $tocPageFactory;
        $this->pagesRepository      = $pagesRepository;
        $this->taxonomyRepository   = $taxonomyRepository;
    }
    
    public function editTocPage( $tocId, Request $request ): Response
    {
        $locale         = $request->getLocale();
        $tocRootPage    = $this->documentRepository->find( $tocId )->getTocRootPage();
        
        $tocPageId      = (int)$request->query->get( 'toc-page-id' );
        if ( $tocPageId ) {
            $oTocPage   = $this->tocPageRepository->find( $tocPageId );
        } else {
            $oTocPage   = $this->tocPageFactory->createNew();
        }
        
        $form           = $this->createForm( TocPageForm::class, $oTocPage, [
            'data'          => $oTocPage,
            'method'        => 'POST',
            'tocRootPage'   => $tocRootPage
        ]);
        
        return $this->render( '@VSCms/Pages/Document/form/toc_page.html.twig', [
            'form'  => $form->createView(),
            'tocId' => $tocId,
            'item'  => $oTocPage,
        ]);
    }
    
    public function handleTocPage( $tocId, Request $request ): Response
    {
        $tocPageId      = $this->tocPageRepository->find( $_POST['toc_page_form']['id'] );
        $parentTocPage  = $this->tocPageRepository->find( $_POST['toc_page_form']['parent'] );
        $linkedPage     = $this->pagesRepository->find( $_POST['toc_page_form']['page'] );
        
        if ( $tocPageId ) {
            $oTocPage   = $this->tocPageRepository->find( $tocPageId );
        } else {
            $oTocPage   = $this->tocPageFactory->createNew();
        }
        $form   = $this->createForm( TocPageForm::class, $oTocPage );
        
        $form->handleRequest( $request );
        if ( $form->isSubmitted()  ) { // && $form->isValid()
            $em             = $this->getDoctrine()->getManager();
            
            $oTocPage->setParent( $parentTocPage );
            $oTocPage->setPage( $linkedPage );
            
            $em->persist( $oTocPage );
            $em->flush();
            
            return $this->redirect( $this->generateUrl( 'vs_cms_multipage_toc_update', ['id' => $tocId] ) );
        }
        
        return new Response( 'The form is not submited properly !!!', 500 );
    }
    
    public function gtreeTableSource( $tocId, Request $request ): Response
    {
        $parentId   = (int)$request->query->get( 'parentId' );
        
        return new JsonResponse( $this->gtreeTableData( $tocId, $parentId ) );
    }
    
    public function easyuiComboTreeSource( $tocId, Request $request ): Response
    {
        return new JsonResponse( $this->easyuiComboTreeData( $tocId ) );
    }
    
    protected function gtreeTableData( $tocId, $parentId ): array
    {
        $parent = $parentId ? $this->tocPageRepository->find( $parentId ) : $this->documentRepository->find( $tocId )->getTocRootPage();
        
        $gtreeTableData = [];
        $children       = $this->tocPageRepository->findBy( ['parent' => $parent] );
        foreach ( $children as $c ) {
            $gtreeTableData[] = [
                'id'        => (int)$c->getId(),
                'name'      => $c->getTitle(),
                'level'     => (int)$c->getLevel(),
                'type'      => "default"
            ];
        }
        
        return ['nodes' => $gtreeTableData];
    }
    
    protected function easyuiComboTreeData( $tocId ) : array
    {
        $root       = $this->documentRepository->find( $tocId )->getTocRootPage();
        $data       = [];

        $data[0]    = [
            'id'        => $root->getId(),
            'text'      => $root->getTitle(),
            'children'  => []
        ];
        
        $this->buildEasyuiCombotreeData( $root->getChildren(), $data[0]['children'], [] );
    
        return $data;
    }
    
    protected function buildEasyuiCombotreeData( $tree, &$data, array $selectedValues )
    {
        $key    = 0;
        foreach( $tree as $node ) {
            $data[$key]   = [
                'id'        => $node->getId(),
                'text'      => $node->getTitle(),
                'children'  => []
            ];
            if ( in_array( $node->getId(), $selectedValues ) ) {
                $data[$key]['checked'] = true;
            }
            
            if ( $node->getChildren()->count() ) {
                $this->buildEasyuiCombotreeData( $node->getChildren(), $data[$key]['children'], $selectedValues );
            }
            
            $key++;
        }
    }
}

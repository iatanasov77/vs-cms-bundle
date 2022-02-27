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
    
    public function editTocPage( $documentId, Request $request ): Response
    {
        $locale         = $request->getLocale();
        $tocRootPage    = $this->documentRepository->find( $documentId )->getTocRootPage();
        
        $tocPageId      = (int)$request->query->get( 'toc-page-id' );
        $oTocPage       = $tocPageId ? $this->tocPageRepository->find( $tocPageId ) : $this->tocPageFactory->createNew();
        
        $form           = $this->createForm( TocPageForm::class, $oTocPage, [
            'data'          => $oTocPage,
            'method'        => 'POST',
            'tocRootPage'   => $tocRootPage
        ]);
        
        return $this->render( '@VSCms/Pages/Document/form/toc_page.html.twig', [
            'form'          => $form->createView(),
            'documentId'    => $documentId,
            'item'          => $oTocPage,
        ]);
    }
    
    public function handleTocPage( $documentId, Request $request ): Response
    {
        $oTocPage       = $this->tocPageFactory->createNew();
        $tocRootPage    = $this->documentRepository->find( $documentId )->getTocRootPage();
        $form           = $this->createForm( TocPageForm::class, $oTocPage, [
            'data'          => $oTocPage,
            'method'        => 'POST',
            'tocRootPage'   => $tocRootPage
        ]);
        
        $form->handleRequest( $request );
        if ( $form->isSubmitted()  ) { // && $form->isValid()
            $em         = $this->getDoctrine()->getManager();
            
            $translatableLocale = $request->getLocale();
            $tocPageName        = $form['title']->getData();
            //$rootTocPageName        = $entity->getTitle()
            
            $tocPage    = $form->getData();
            $tocPage->setRoot( $tocRootPage );
            
            if ( ! $tocPage->getId() ) {
                $this->initNewTocPage( $tocPage, $tocPageName, $translatableLocale );
            }
            
            if ( ! $tocPage->getParent() ) {
                $tocPage->setParent( $tocRootPage );
            }
            $em->persist( $oTocPage );
            $em->flush();
            
            return $this->redirect( $this->generateUrl( 'vs_cms_document_update', ['id' => $documentId] ) );
        }
        
        return new Response( 'The form is not submited properly !!!', 500 );
    }
    
    public function gtreeTableSource( $documentId, Request $request ): Response
    {
        $parentId   = (int)$request->query->get( 'parentId' );
        
        return new JsonResponse( $this->gtreeTableData( $documentId, $parentId ) );
    }
    
    public function easyuiComboTreeSource( $documentId, Request $request ): Response
    {
        return new JsonResponse( $this->easyuiComboTreeData( $documentId ) );
    }
    
    protected function gtreeTableData( $documentId, $parentId ): array
    {
        $parent = $parentId ? $this->tocPageRepository->find( $parentId ) : $this->documentRepository->find( $documentId )->getTocRootPage();
        
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
    
    protected function easyuiComboTreeData( $documentId ) : array
    {
        $root       = $this->documentRepository->find( $documentId )->getTocRootPage();
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
    
    protected function initNewTocPage( &$tocPage, $title, $locale )
    {
        $taxonomy               = $this->taxonomyRepository->findByCode(
            $this->getParameter( 'vs_application.document_pages.taxonomy_code' )
        );
        $newTaxon   = $this->createTaxon(
            'Root TocPage of Document: "' . $title . '"',
            $locale,
            null,
            $taxonomy->getId()
        );
        
        $tocPage->setTaxon( $newTaxon );
    }
}

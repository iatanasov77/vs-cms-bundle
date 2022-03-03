<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Vankosoft\CmsBundle\Repository\TocPagesRepository;
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
    
    public function __construct(
        DocumentsRepository $documentRepository,
        TocPagesRepository $tocPageRepository,
        FactoryInterface $tocPageFactory
    ) {
        $this->documentRepository   = $documentRepository;
        $this->tocPageRepository    = $tocPageRepository;
        $this->tocPageFactory       = $tocPageFactory;
    }
    
    public function editTocPage( $documentId, $tocPageId, Request $request ): Response
    {
        $locale         = $request->getLocale();
        $tocRootPage    = $this->documentRepository->find( $documentId )->getTocRootPage();
        
        if ( intval( $tocPageId ) ) {
            $oTocPage   = $this->tocPageRepository->find( $tocPageId );
            $formAction = $this->generateUrl( 'vs_cms_toc_page_update', ['documentId' => $documentId, 'id' => $tocPageId] );
            $formMethod = 'PUT';
        } else {
            $oTocPage   = $this->tocPageFactory->createNew();
            $formAction = $this->generateUrl( 'vs_cms_toc_page_create', ['documentId' => $documentId] );
            $formMethod = 'POST';
        }
        
        $form           = $this->createForm( TocPageForm::class, $oTocPage, [
            'action'                        => $formAction,
            'method'                        => $formMethod,
            'data'                          => $oTocPage,
            'tocRootPage'                   => $tocRootPage,
            
            'ckeditor_uiColor'              => $this->getParameter( 'vs_cms.form.toc_page.ckeditor_uiColor' ),
            'ckeditor_extraAllowedContent'  => $this->getParameter( 'vs_cms.form.toc_page.ckeditor_extraAllowedContent' ),
            'ckeditor_toolbar'              => $this->getParameter( 'vs_cms.form.toc_page.ckeditor_toolbar' ),
            'ckeditor_extraPlugins'         => $this->getParameter( 'vs_cms.form.toc_page.ckeditor_extraPlugins' ),
            'ckeditor_removeButtons'        => $this->getParameter( 'vs_cms.form.toc_page.ckeditor_removeButtons' ),
        ]);
        
        return $this->render( '@VSCms/Pages/Document/form/toc_page.html.twig', [
            'form'          => $form->createView(),
            'documentId'    => $documentId,
            'item'          => $oTocPage,
        ]);
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
}

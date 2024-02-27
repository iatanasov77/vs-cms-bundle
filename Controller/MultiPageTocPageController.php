<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Vankosoft\CmsBundle\Repository\TocPagesRepository;
use Vankosoft\CmsBundle\Form\TocPageForm;
use Vankosoft\CmsBundle\Repository\DocumentsRepository;
use Vankosoft\ApplicationBundle\Component\Status;

class MultiPageTocPageController extends AbstractController
{
    /** @var ManagerRegistry */
    private $doctrine;
    
    /** @var DocumentsRepository */
    private $documentRepository;
    
    /** @var TocPagesRepository */
    private $tocPageRepository;
    
    /** @var FactoryInterface */
    private $tocPageFactory;
    
    public function __construct(
        ManagerRegistry $doctrine,
        DocumentsRepository $documentRepository,
        TocPagesRepository $tocPageRepository,
        FactoryInterface $tocPageFactory
    ) {
        $this->doctrine             = $doctrine;
        $this->documentRepository   = $documentRepository;
        $this->tocPageRepository    = $tocPageRepository;
        $this->tocPageFactory       = $tocPageFactory;
    }
    
    public function sortAction( $id, $insertAfterId, Request $request ): Response
    {
        $em             = $this->doctrine->getManager();
        $item           = $this->tocPageRepository->find( $id );
        $insertAfter    = $this->tocPageRepository->find( $insertAfterId );
        $this->tocPageRepository->insertAfter( $item, $insertAfterId );

        $position       = $insertAfter ? ( $insertAfter->getPosition() + 1 ) : 1;
        $item->setPosition( $position );
        $em->persist( $item );
        $em->flush();
        
        return new JsonResponse([
            'status'   => Status::STATUS_OK
        ]);
    }
    
    public function editTocPage( $documentId, $tocPageId, $locale, Request $request ): Response
    {
        $tocRootPage    = $this->documentRepository->find( $documentId )->getTocRootPage();
        $em             = $this->doctrine->getManager();
        
        if ( intval( $tocPageId ) ) {
            $oTocPage   = $this->tocPageRepository->find( $tocPageId );
            $formAction = $this->generateUrl( 'vs_cms_toc_page_update', ['documentId' => $documentId, 'id' => $tocPageId] );
            $formMethod = 'PUT';
        } else {
            $oTocPage   = $this->tocPageFactory->createNew();
            $formAction = $this->generateUrl( 'vs_cms_toc_page_create', ['documentId' => $documentId] );
            $formMethod = 'POST';
        }
        
        if ( $locale != $request->getLocale() ) {
            $oTocPage->setTranslatableLocale( $locale );
            $em->refresh( $oTocPage );
        }
        
        $form           = $this->createForm( TocPageForm::class, $oTocPage, [
            'action'                        => $formAction,
            'method'                        => $formMethod,
            'data'                          => $oTocPage,
            'tocRootPage'                   => $tocRootPage,
            
            'ckeditor_uiColor'              => $this->getParameter( 'vs_cms.form.toc_page.ckeditor_uiColor' ),
            'ckeditor_toolbar'              => $this->getParameter( 'vs_cms.form.toc_page.ckeditor_toolbar' ),
            'ckeditor_extraPlugins'         => $this->getParameter( 'vs_cms.form.toc_page.ckeditor_extraPlugins' ),
            'ckeditor_removeButtons'        => $this->getParameter( 'vs_cms.form.toc_page.ckeditor_removeButtons' ),
            'ckeditor_allowedContent'       => $this->getParameter( 'vs_cms.form.toc_page.ckeditor_allowedContent' ),
            'ckeditor_extraAllowedContent'  => $this->getParameter( 'vs_cms.form.toc_page.ckeditor_extraAllowedContent' ),
        ]);
        
        return $this->render( '@VSCms/Pages/Document/form/toc_page.html.twig', [
            'form'          => $form->createView(),
            'documentId'    => $documentId,
            'item'          => $oTocPage,
        ]);
    }
    
    public function deleteTocPage( $documentId, $tocPageId, Request $request ): Response
    {
        $em         = $this->doctrine->getManager();
        $oTocPage   = $this->tocPageRepository->find( $tocPageId );
        
        $em->remove( $oTocPage );
        $em->flush();
        
        return $this->redirectToRoute( 'vs_cms_document_update', ['id' => $documentId] );
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

        /* With Root Document Page
        $data[0]    = [
            'id'        => $root->getId(),
            'text'      => $root->getTitle(),
            'children'  => []
        ];
        $this->buildEasyuiCombotreeData( $root->getChildren(), $data[0]['children'], [] );
        */
        
        // Without Root Document Page
        $this->buildEasyuiCombotreeData( $root->getChildren(), $data, [] );
    
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

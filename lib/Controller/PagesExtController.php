<?php  namespace VS\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use VS\ApplicationBundle\Repository\LogEntryRepository;
use VS\ApplicationBundle\Repository\TaxonomyRepository;
use VS\ApplicationBundle\Controller\TaxonomyTreeDataTrait;
use VS\CmsBundle\Repository\PagesRepository;
use VS\CmsBundle\Form\ClonePageForm;
use VS\CmsBundle\Form\PageForm;
use VS\CmsBundle\Repository\PageCategoryRepository;

class PagesExtController extends AbstractController
{
    use TaxonomyTreeDataTrait;
    
    public function getPageForm( TaxonomyRepository $taxonomyRepository, PagesRepository $pagesRepository, $pageId, $locale, Request $request ) : Response
    {
        $em         = $this->get( 'doctrine.orm.entity_manager' );
        $repository = $pagesRepository;
        $page       = $repository->find( $pageId );
        
        if ( $locale != $request->getLocale() ) {
            $page->setTranslatableLocale( $locale );
            $em->refresh( $page );
        }
        
        if ( $this->container->hasParameter( 'vs_cms.page_categories.taxonomy_id' ) ) {
            $taxonomyId = $this->getParameter( 'vs_cms.page_categories.taxonomy_id' );
        } else {
            $taxonomyCode   = $this->getParameter( 'vs_application.page_categories.taxonomy_code' );
            $taxonomyId     = $taxonomyRepository->findByCode( $taxonomyCode )->getId();
        }
        
        return $this->render( '@VSCms/Pages/partial/page_form.html.twig', [
            'categories'    => $this->get( 'vs_cms.repository.page_categories' )->findAll(),
            'taxonomyId'    => $taxonomyId,
            'item'          => $page,
            'form'          => $this->createForm( PageForm::class, $page )->createView(),
        ]);
    }
    
    public function clonePage( PagesRepository $pagesRepository, $pageId, Request $request ) : Response
    {
        $parentPage = $pagesRepository->find( $pageId );
        $formClone  = $this->createForm( ClonePageForm::class );
        
        $formClone->handleRequest( $request );
        if( $formClone->isSubmitted() ) {
            if ( ! $formClone->isValid() ) {
                return new Response( 'The form is not valid !!!', Response::HTTP_BAD_REQUEST );
            }
            
            $em     = $this->getDoctrine()->getManager();
            $oPage  = $this->get( 'vs_cms.factory.pages' )->createNew();
            $data   = $formClone->getData();
            
            $oPage->setTitle( $data['newTitle'] );
            $oPage->setText( $parentPage->getText() );
            foreach ( $parentPage->getCategories() as $category ) {
                $oPage->addCategory( $category );
            }
            
            $em->persist( $oPage );
            $em->flush();
            
            return $this->redirect( $this->generateUrl( 'vs_cms_pages_update', ['id' => $oPage->getId()] ) );
        }
        
        return new Response( 'The form is not hanled properly !!!', Response::HTTP_BAD_REQUEST );
    }
    
    public function previewPage( LogEntryRepository $logentryRepository, PagesRepository $pagesRepository, $pageId, $locale, $version, Request $request ) : Response
    {
        $em     = $this->get( 'doctrine.orm.entity_manager' );
        $page   = $pagesRepository->find( $pageId );
        $layout = $request->query->get( 'layout' );
        
        if ( $locale != $request->getLocale() ) {
            $page->setTranslatableLocale( $locale );
            $em->refresh( $page );
        }
        
        if ( $version ) {
            $erLogs = $logentryRepository;
            $erLogs->revertByLocale( $page, $version, $locale ); // This only load Entity Data for concrete Version 
                                                                 // from LogEntry Repository but not persist it
        }
        
        return $this->render( '@VSCms/Pages/show.html.twig', [
            'page'      => $page,
            'layout'    => $layout,
        ]);
    }
    
    public function gtreeTableSource( TaxonomyRepository $taxonomyRepository, $taxonomyId, Request $request ): Response
    {
        $this->taxonomyRepository   = $taxonomyRepository;
        $parentId                   = (int)$request->query->get( 'parentTaxonId' );
        
        return new JsonResponse( $this->gtreeTableData( $taxonomyId, $parentId ) );
    }
    
    public function easyuiComboTreeSource( TaxonomyRepository $taxonomyRepository, $taxonomyId, Request $request ): Response
    {
        $this->taxonomyRepository   = $taxonomyRepository;
        
        return new JsonResponse( $this->easyuiComboTreeData( $taxonomyId ) );
    }
    
    public function easyuiComboTreeWithSelectedSource( TaxonomyRepository $taxonomyRepository, PagesRepository $pagesRepository, $pageId, $taxonomyId, Request $request ): Response
    {
        $this->taxonomyRepository   = $taxonomyRepository;
        
        return new JsonResponse( $this->easyuiComboTreeData( $taxonomyId, $this->getSelectedCategoryTaxons( $pagesRepository, $pageId ) ) );
    }
    
    public function easyuiComboTreeWithLeafsSource( TaxonomyRepository $taxonomyRepository, PageCategoryRepository $categoryRepository, $taxonomyId, Request $request ): Response
    {
        $this->taxonomyRepository   = $taxonomyRepository;
        
        return new JsonResponse( $this->easyuiComboTreeData( $taxonomyId, [], $this->getCategoryPagesByTaxons( $categoryRepository ) ) );
    }
    
    public function deleteCategory_ByTaxonId( PageCategoryRepository $categoryRepository, $taxonId, Request $request )
    {
        $em         = $this->getDoctrine()->getManager();
        $pcr        = $categoryRepository;
        $category   = $pcr->findOneBy( ['taxon' => $taxonId] );
        
        $em->remove( $category );
        $em->flush();
        
        return new JsonResponse([
            'status'   => 'SUCCESS'
        ]);
    }
    
    public function updateCategory_ByTaxonId( $taxonId, Request $request )
    {
        $em         = $this->getDoctrine()->getManager();
        
        //$category       = $this->getPageCategoryRepository()->findOneBy( ['taxon' => $taxonId] );
        //$em->persist( $category );
        
        $categoryTaxon  = $this->getTaxonRepository()->find( $taxonId );
        $categoryTaxon->setName( $request->get( 'name' ) );
        
        $em->persist( $categoryTaxon );
        $em->flush();
        
        return new JsonResponse([
            'status'   => 'SUCCESS'
        ]);
    }
    
    /**
     * The NestedTreeRepository has some useful functions
     * to interact with NestedSet tree. Repository uses
     * the strategy used by listener
     *
     * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
     * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
     * @method persistAsFirstChild($node)
     * @method persistAsFirstChildOf($node, $parent)
     * @method persistAsLastChild($node)
     * @method persistAsLastChildOf($node, $parent)
     * @method persistAsNextSibling($node)
     * @method persistAsNextSiblingOf($node, $sibling)
     * @method persistAsPrevSibling($node)
     * @method persistAsPrevSiblingOf($node, $sibling)
     */
    public function moveCategory_ByTaxonId( $sourceTaxonId, $destinationTaxonId, $position, Request $request )
    {
        $repo               = $this->getTaxonRepository();
        $sourceTaxon        = $repo->find( $sourceTaxonId );
        $destinationTaxon   = $repo->find( $destinationTaxonId );
        
        //$repo->persistAsFirstChildOf( $sourceTaxon, $sourceTaxon->getRoot() );
        switch ( $position ) {
            case 'before':
                $repo->persistAsPrevSiblingOf( $sourceTaxon, $destinationTaxon );
                break;
            case 'after':
                $repo->persistAsNextSiblingOf( $sourceTaxon, $destinationTaxon );
                break;
            case 'lastChild':
                $repo->persistAsLastChildOf( $sourceTaxon, $destinationTaxon );
                break;
            default:
                
        }
        
        $this->getDoctrine()->getManager()->flush();
        
        return new JsonResponse(['status' => 'SUCCESS']);
    }
    
    protected function getSelectedCategoryTaxons( $pagesRepository, $pageId ): array
    {
        $selected   = [];
        $page       = $pagesRepository->find( $pageId );
        if ( $page ) {
            foreach( $page->getCategories() as $cat ) {
                $selected[] = $cat->getTaxon()->getId();
            }
        }
        
        return $selected;
    }
    
    protected function getCategoryPagesByTaxons( $categoryRepository ): array
    {
        $leafs  = [];
        foreach ( $categoryRepository->findAll() as $category ) {
            $pages  = $category->getPages();
            if ( $pages->count() ) {
                $leafs[$category->getTaxon()->getId()]  = $pages;
            }
        }
        
        return $leafs;
    }
}

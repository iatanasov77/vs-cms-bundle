<?php  namespace Vankosoft\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

use Vankosoft\ApplicationBundle\Repository\LogEntryRepository;
use Vankosoft\ApplicationBundle\Repository\TaxonomyRepository;
use Vankosoft\ApplicationBundle\Repository\TaxonRepository;
use Vankosoft\ApplicationBundle\Controller\Traits\TaxonomyTreeDataTrait;
use Vankosoft\ApplicationBundle\Controller\Traits\CategoryTreeDataTrait;
use Vankosoft\CmsBundle\Repository\PagesRepository;
use Vankosoft\CmsBundle\Form\ClonePageForm;
use Vankosoft\CmsBundle\Form\PageForm;
use Vankosoft\CmsBundle\Repository\PageCategoryRepository;

class PagesExtController extends AbstractController
{
    use TaxonomyTreeDataTrait;
    use CategoryTreeDataTrait;
    
    /** @var ManagerRegistry */
    protected ManagerRegistry $doctrine;
    
    /** @var PagesRepository */
    protected PagesRepository $pagesRepository;
    
    /** @var PageCategoryRepository */
    protected PageCategoryRepository $pagesCategoriesRepository;
    
    /** @var LogEntryRepository */
    protected LogEntryRepository $logentryRepository;
    
    /** @var Factory */
    protected Factory $pagesFactory;
    
    /** @var EntityRepository */
    protected $vsTagsWhitelistContextRepository;
    
    public function __construct(
        ManagerRegistry $doctrine,
        TaxonomyRepository $taxonomyRepository,
        TaxonRepository $taxonRepository,
        PagesRepository $pagesRepository,
        PageCategoryRepository $pagesCategoriesRepository,
        LogEntryRepository $logentryRepository,
        Factory $pagesFactory,
        EntityRepository $vsTagsWhitelistContextRepository,
    ) {
        $this->doctrine                         = $doctrine;
        $this->taxonomyRepository               = $taxonomyRepository;
        $this->taxonRepository                  = $taxonRepository;
        $this->pagesRepository                  = $pagesRepository;
        $this->pagesCategoriesRepository        = $pagesCategoriesRepository;
        $this->logentryRepository               = $logentryRepository;
        $this->pagesFactory                     = $pagesFactory;
        $this->vsTagsWhitelistContextRepository = $vsTagsWhitelistContextRepository;
    }
    
    public function getPageForm( $itemId, $locale, Request $request ): Response
    {
        $em     = $this->doctrine->getManager();
        $item   = $this->pagesRepository->find( $itemId );
        
        if ( $locale != $request->getLocale() ) {
            $item->setTranslatableLocale( $locale );
            $em->refresh( $item );
        }
        
        $taxonomy       = $this->taxonomyRepository->findByCode(
                                                    $this->getParameter( 'vs_application.page_categories.taxonomy_code' )
                                                );
        $tagsContext    = $this->vsTagsWhitelistContextRepository->findByTaxonCode( 'static-pages' );
        
        return $this->render( '@VSCms/Pages/Pages/partial/page_form.html.twig', [
            'categories'    => $this->pagesCategoriesRepository->findAll(),
            'taxonomyId'    => $taxonomy ? $taxonomy->getId() : 0,
            'item'          => $item,
            'form'          => $this->createForm( PageForm::class, $item )->createView(),
            
            'pageTags'      => $tagsContext->getTagsArray(),
        ]);
    }
    
    public function clonePage( $pageId, Request $request ): Response
    {
        $parentPage = $this->pagesRepository>find( $pageId );
        $formClone  = $this->createForm( ClonePageForm::class );
        
        $formClone->handleRequest( $request );
        if( $formClone->isSubmitted() ) {
            if ( ! $formClone->isValid() ) {
                return new Response( 'The form is not valid !!!', Response::HTTP_BAD_REQUEST );
            }
            
            $em     = $this->doctrine->getManager();
            $oPage  = $this->pagesFactory->createNew();
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
    
    public function previewPage( $pageId, $locale, $version, Request $request ): Response
    {
        $em     = $this->doctrine->getManager();
        $page   = $this->pagesRepository->find( $pageId );
        $layout = $request->query->get( 'layout' );
        
        if ( $locale != $request->getLocale() ) {
            $page->setTranslatableLocale( $locale );
            $em->refresh( $page );
        }
        
        if ( $version ) {
            $this->logentryRepository->revertByLocale( $page, $version, $locale );  // This only load Entity Data for concrete Version 
                                                                                    // from LogEntry Repository but not persist it
        }
        
        return $this->render( '@VSCms/Pages/Pages/show.html.twig', [
            'page'      => $page,
            'layout'    => $layout,
        ]);
    }
    
    public function gtreeTableSource( $taxonomyId, Request $request ): Response
    {
        $parentId                   = (int)$request->query->get( 'parentTaxonId' );
        
        return new JsonResponse( $this->gtreeTableData( $taxonomyId, $parentId ) );
    }
    
    public function easyuiComboTreeSource( $taxonomyId, Request $request ): Response
    {
        return new JsonResponse( $this->easyuiComboTreeData( $taxonomyId ) );
    }
    
    public function easyuiComboTreeWithSelectedSource( $pageId, $taxonomyId, Request $request ): Response
    {
        // OLD WAY
        //return new JsonResponse( $this->easyuiComboTreeData( $taxonomyId, $this->getSelectedCategoryTaxons( $pageId ) ) );
        
        $editPage           = $pageId ? $this->pagesRepository->find( $pageId ) : null;
        $selectedCategories = $editPage  ? $editPage->getCategories()->toArray() : [];
        $data               = [];
        
        $topCategories      = new ArrayCollection( $this->pagesCategoriesRepository->findBy( ['parent' => null] ) );
        
        $categoriesTree     = [];
        $this->getItemsTree( $topCategories, $categoriesTree );
        $this->buildEasyuiCombotreeDataFromCollection( $categoriesTree, $data, $selectedCategories );
        
        return new JsonResponse( $data );
    }
    
    public function easyuiComboTreeWithLeafsSource( $taxonomyId, Request $request ): Response
    {
        return new JsonResponse( $this->easyuiComboTreeData( $taxonomyId, [], $this->getCategoryPagesByTaxons() ) );
    }
    
    public function deleteCategory_ByTaxonId( $taxonId, Request $request )
    {
        $em         = $this->doctrine->getManager();
        $category   = $this->pagesCategoriesRepository->findOneBy( ['taxon' => $taxonId] );
        
        $em->remove( $category );
        $em->flush();
        
        return new JsonResponse([
            'status'   => 'SUCCESS'
        ]);
    }
    
    public function updateCategory_ByTaxonId( $taxonId, Request $request )
    {
        $em         = $this->doctrine->getManager();
        
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
        
        $this->doctrine->getManager()->flush();
        
        return new JsonResponse(['status' => 'SUCCESS']);
    }
    
    protected function getSelectedCategoryTaxons( $pageId ): array
    {
        $selected   = [];
        $page       = $this->pagesRepository->find( $pageId );
        if ( $page ) {
            foreach( $page->getCategories() as $cat ) {
                $selected[] = $cat->getTaxon()->getId();
            }
        }
        
        return $selected;
    }
    
    protected function getCategoryPagesByTaxons(): array
    {
        $leafs  = [];
        foreach ( $this->pagesCategoriesRepository->findAll() as $category ) {
            $pages  = $category->getPages();
            if ( $pages->count() ) {
                $leafs[$category->getTaxon()->getId()]  = $pages;
            }
        }
        
        return $leafs;
    }
}

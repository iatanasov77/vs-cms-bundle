<?php  namespace VS\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use VS\ApplicationBundle\Controller\TaxonomyTreeDataTrait;

class PagesExtController extends Controller
{
    use TaxonomyTreeDataTrait;
    
    public function gtreeTableSource( $taxonomyId, Request $request ): Response
    {
        $parentId       = (int)$request->query->get( 'parentTaxonId' );
        
        return new JsonResponse( $this->gtreeTableData( $taxonomyId, $parentId ) );
    }
    
    public function easyuiComboTreeSource( $taxonomyId, Request $request ): Response
    {
        return new JsonResponse( $this->easyuiComboTreeData( $taxonomyId ) );
    }
    
    public function deleteCategory_ByTaxonId( $taxonId, Request $request )
    {
        $em         = $this->getDoctrine()->getManager();
        $pcr        = $this->getPageCategoryRepository();
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
    
    protected function getPageCategoryRepository()
    {
        return $this->get( 'vs_cms.repository.page_categories' );
    }
    
    protected function getTaxonRepository()
    {
        return $this->get( 'vs_application.repository.taxon' );
    }
}

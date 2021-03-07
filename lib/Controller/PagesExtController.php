<?php  namespace VS\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use VS\ApplicationBundle\Controller\TaxonomyTreeDataTrait;

class PagesExtController extends Controller
{
    use TaxonomyTreeDataTrait;
    
    public function treeCombo( Request $request ): Response
    {
        $taxonomyId = $this->getParameter( 'vs_cms.page_categories.taxonomy_id' );
        
        return new JsonResponse( $this->easyuiComboTreeData( $taxonomyId ) );
    }
    
    public function treeTable( Request $request ): Response
    {
        $taxonomyId = $this->getParameter( 'vs_cms.page_categories.taxonomy_id' );
        $parentId   = (int)$request->query->get( 'parentTaxonId' );
        
        return new JsonResponse( $this->gtreeTableData( $taxonomyId, $parentId ) );
    }
}

<?php  namespace VS\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PagesExtController extends Controller
{
    private $cr;
    
    public function tree( Request $request ) : JsonResponse
    {
        $this->cr   = $this->getPageCategoryRepository();
        $data       = [];
        
        $this->treeViewData( $this->cr->childrenHierarchy(), $data );
        
        return new JsonResponse( $data );
    }
    
    protected function treeViewData( $tree, &$data )
    {
        foreach( $tree as $k => $node ) {
            $data[$k]   = [
                'id'    => $node['id'],
                'text' => $node['name'],
                'children' => []
            ];
            
            if ( ! empty( $node['__children'] ) ) {
                $this->treeViewData( $node['__children'], $data[$k]['children'] );
            } else {
                foreach( $this->cr->find( $node['id'] )->getPages() as $page ) {
                    $data[$k]['children'][] = [
                        'id'    => $page->getId(),
                        'text'  => $page->getTitle(),
                        //'children' => []
                    ];
                }
            }
        }
    }
    
    protected function getPageCategoryRepository()
    {
        return $this->get( 'vs_cms.repository.page_categories' );
    }
}

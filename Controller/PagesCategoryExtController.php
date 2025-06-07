<?php namespace Vankosoft\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Vankosoft\ApplicationBundle\Controller\Traits\CategoryTreeDataTrait;

class PagesCategoryExtController extends AbstractController
{
    use CategoryTreeDataTrait;
    
    /** @var TranslatorInterface */
    protected $translator;
    
    /** @var RepositoryInterface */
    protected $categoryRepository;
    
    public function __construct(
        TranslatorInterface $translator,
        RepositoryInterface $categoryRepository
    ) {
        $this->translator           = $translator;
        $this->categoryRepository   = $categoryRepository;
    }
    
    public function easyuiComboTreeWithSelectedSource( $categoryId, Request $request ): JsonResponse
    {
        $selectedParent = $categoryId ? $this->categoryRepository->find( $categoryId )->getParent() : null;
        $data[0]           = [
            'id'        => 0,
            'text'      => $this->translator->trans( 'vs_application.form.parent_category_no_parent', [], 'VSApplicationBundle' ),
            'children'  => []
        ];
        
        $topCategories   = $this->categoryRepository->findBy( ['parent' => null] );
        $categoriesTree  = [];
        
        $this->getItemsTree( new ArrayCollection( $topCategories ), $categoriesTree );
        $this->buildEasyuiCombotreeDataFromCollection( $categoriesTree, $data[0]['children'], [$selectedParent] );
        
        return new JsonResponse( $data );
    }
}
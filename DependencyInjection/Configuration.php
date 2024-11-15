<?php namespace Vankosoft\CmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

use Vankosoft\CmsBundle\Model\Page;
use Vankosoft\CmsBundle\Controller\PagesController;
use Vankosoft\CmsBundle\Repository\PagesRepository;
use Vankosoft\CmsBundle\Form\PageForm;

use Vankosoft\CmsBundle\Model\PageCategory;
use Vankosoft\CmsBundle\Controller\PagesCategoryController;
use Vankosoft\CmsBundle\Repository\PageCategoryRepository;
use Vankosoft\CmsBundle\Form\PageCategoryForm;

use Vankosoft\CmsBundle\Model\Document;
use Vankosoft\CmsBundle\Controller\DocumentController;
use Vankosoft\CmsBundle\Repository\DocumentsRepository;
use Vankosoft\CmsBundle\Form\DocumentForm;

use Vankosoft\CmsBundle\Model\TocPage;
use Vankosoft\CmsBundle\Controller\TocPageController;
use Vankosoft\CmsBundle\Repository\TocPagesRepository;
use Vankosoft\CmsBundle\Form\TocPageForm;

use Vankosoft\CmsBundle\Model\FileManager;
use Vankosoft\CmsBundle\Repository\FileManagerRepository;
use Vankosoft\CmsBundle\Controller\VankosoftFileManagerController;
use Vankosoft\CmsBundle\Form\VankosoftFileManagerForm;

use Vankosoft\CmsBundle\Model\FileManagerFile;
use Vankosoft\CmsBundle\Controller\VankosoftFileManagerFileController;
use Vankosoft\CmsBundle\Form\VankosoftFileManagerFileForm;

use Vankosoft\CmsBundle\Model\DocumentCategory;
use Vankosoft\CmsBundle\Controller\DocumentCategoryController;
use Vankosoft\CmsBundle\Repository\DocumentCategoryRepository;
use Vankosoft\CmsBundle\Form\DocumentCategoryForm;

use Vankosoft\CmsBundle\Model\HelpCenterQuestion;
use Vankosoft\CmsBundle\Controller\HelpCenterQuestionController;
use Vankosoft\CmsBundle\Form\HelpCenterQuestionForm;

use Vankosoft\CmsBundle\Model\QuickLink;
use Vankosoft\CmsBundle\Controller\QuickLinkController;
use Vankosoft\CmsBundle\Form\QuickLinkForm;

use Vankosoft\CmsBundle\Model\Slider;
use Vankosoft\CmsBundle\Controller\SliderController;
use Vankosoft\CmsBundle\Repository\SliderRepository;
use Vankosoft\CmsBundle\Form\SliderForm;

use Vankosoft\CmsBundle\Model\SliderItem;
use Vankosoft\CmsBundle\Repository\SliderItemRepository;
use Vankosoft\CmsBundle\Controller\SliderItemController;
use Vankosoft\CmsBundle\Form\SliderItemForm;

use Vankosoft\CmsBundle\Model\SliderItemPhoto;

use Vankosoft\CmsBundle\Model\BannerPlace;
use Vankosoft\CmsBundle\Controller\BannerPlaceController;
use Vankosoft\CmsBundle\Repository\BannerPlaceRepository;
use Vankosoft\CmsBundle\Form\BannerPlaceForm;

use Vankosoft\CmsBundle\Model\Banner;
use Vankosoft\CmsBundle\Controller\BannerController;
use Vankosoft\CmsBundle\Repository\BannerRepository;
use Vankosoft\CmsBundle\Form\BannerForm;

use Vankosoft\CmsBundle\Model\BannerImage;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder    = new TreeBuilder( 'vs_cms' );
        $rootNode       = $treeBuilder->getRootNode();
        
        $rootNode
            ->children()
                ->scalarNode( 'driver' )->defaultValue( SyliusResourceBundle::DRIVER_DOCTRINE_ORM )->cannotBeEmpty()->end()
            ->end()
        ;
        
        $this->addResourcesSection( $rootNode );
            
        return $treeBuilder;
    }

    private function addResourcesSection( ArrayNodeDefinition $node ): void
    {
        $node
            ->children()
                ->arrayNode( 'resources' )
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode( 'pages' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( Page::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( PagesController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( PagesRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( PageForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode( 'page_categories' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( PageCategory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( PagesCategoryController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( PageCategoryRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( PageCategoryForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode( 'document_categories' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( DocumentCategory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( DocumentCategoryController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( DocumentCategoryRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( DocumentCategoryForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode( 'document' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( Document::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( DocumentController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( DocumentsRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( DocumentForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode( 'toc_page' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( TocPage::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( TocPageController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( TocPagesRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( TocPageForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode( 'file_manager' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( FileManager::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( VankosoftFileManagerController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( FileManagerRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( VankosoftFileManagerForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode( 'file_manager_file' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( FileManagerFile::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( VankosoftFileManagerFileController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( EntityRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( VankosoftFileManagerFileForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        
                        ->arrayNode( 'helpcenter_question' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( HelpCenterQuestion::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( EntityRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( HelpCenterQuestionController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( HelpCenterQuestionForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        
                        ->arrayNode( 'quick_link' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( QuickLink::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( EntityRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( QuickLinkController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( QuickLinkForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        
                        ->arrayNode( 'slider' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( Slider::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( SliderRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( SliderController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( SliderForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        
                        ->arrayNode( 'slider_item' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( SliderItem::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( SliderItemRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( SliderItemController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( SliderItemForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        
                        ->arrayNode( 'slider_item_photo' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( SliderItemPhoto::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( EntityRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        
                        ->arrayNode( 'banner_place' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( BannerPlace::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( BannerPlaceRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( BannerPlaceController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( BannerPlaceForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        
                        ->arrayNode( 'banner' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( Banner::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( BannerRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( BannerController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( BannerForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        
                        ->arrayNode( 'banner_image' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( BannerImage::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( EntityRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        
                    ->end()
                ->end()
            ->end()
        ;
    }
}

<?php namespace VS\CmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

use VS\CmsBundle\Model\Page;
use VS\CmsBundle\Controller\PagesController;
use VS\CmsBundle\Repository\PagesRepository;
use VS\CmsBundle\Form\PageForm;

use VS\CmsBundle\Model\PageCategory;
use VS\CmsBundle\Controller\PagesCategoryController;
use VS\CmsBundle\Repository\PageCategoryRepository;
use VS\CmsBundle\Form\PageCategoryForm;

use VS\CmsBundle\Model\MultiPageToc;
use VS\CmsBundle\Controller\MultiPageTocController;
use VS\CmsBundle\Repository\MultiPageTocRepository;use VS\CmsBundle\Repository\MultiPageTocRepository;
use VS\CmsBundle\Form\MultiPageTocForm;

use VS\CmsBundle\Model\TocPage;
use VS\CmsBundle\Controller\TocPageController;
use VS\CmsBundle\Repository\TocPagesRepository;
use VS\CmsBundle\Form\TocPageForm;


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
    public function getConfigTreeBuilder()
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
                                        ->scalarNode( 'interface' )->defaultValue( ResourceInterface::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( PagesController::class )->cannotBeEmpty()->end()
                                        //->scalarNode( 'repository' )->cannotBeEmpty()->end()
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
                                        ->scalarNode( 'interface' )->defaultValue( ResourceInterface::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( PagesCategoryController::class )->cannotBeEmpty()->end()
                                        //->scalarNode( 'repository' )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( PageCategoryRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( PageCategoryForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode( 'multipage_toc' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( MultiPageToc::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'interface' )->defaultValue( ResourceInterface::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( MultiPageTocController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( EntityRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( MultiPageTocForm::class )->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode( 'multipage_toc_page' )
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode( 'options' )->end()
                                ->arrayNode( 'classes' )
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode( 'model' )->defaultValue( TocPage::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'interface' )->defaultValue( ResourceInterface::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'controller' )->defaultValue( TocPageController::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'repository' )->defaultValue( TocPagesRepository::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'factory' )->defaultValue( Factory::class )->cannotBeEmpty()->end()
                                        ->scalarNode( 'form' )->defaultValue( TocPageForm::class )->cannotBeEmpty()->end()
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

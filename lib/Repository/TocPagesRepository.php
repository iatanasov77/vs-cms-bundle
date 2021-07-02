<?php namespace VS\CmsBundle\Repository;

use Sylius\Component\Resource\Repository\RepositoryInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * 
 * Sylius RepositoryInterface contains 3 abstract methods and must therefore be declared abstract 
 * or implement the remaining methods 
 * (
 *      Sylius\Component\Resource\Repository\RepositoryInterface::createPaginator, 
 *      Sylius\Component\Resource\Repository\RepositoryInterface::add, 
 *      Sylius\Component\Resource\Repository\RepositoryInterface::remove
 * )
 *
 */
class TocPagesRepository extends NestedTreeRepository // implements RepositoryInterface
{
    
}

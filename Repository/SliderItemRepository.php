<?php namespace Vankosoft\CmsBundle\Repository;

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;

class SliderItemRepository extends SortableRepository implements RepositoryInterface
{
    use ResourceRepositoryTrait;
}
<?php namespace Vankosoft\ApplicationBundle\Model\Interfaces;

interface TaxonDescendentInterface
{
    public function getTaxon(): ?TaxonInterface;
    public function getSlug(): string;
    public function getCode(): string;
    public function getName(): string;
    public function getDescription(): ?string;
}

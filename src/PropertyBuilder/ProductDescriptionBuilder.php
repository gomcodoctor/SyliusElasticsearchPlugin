<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTranslationInterface;

final class ProductDescriptionBuilder extends AbstractBuilder
{
    private ConcatedNameResolverInterface $productDescriptionNameResolver;

    public function __construct(ConcatedNameResolverInterface $productDescriptionNameResolver)
    {
        $this->productDescriptionNameResolver = $productDescriptionNameResolver;
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $this->buildProperty(
            $event,
            ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                /** @var ProductTranslationInterface $productTranslation */
                foreach ($product->getTranslations() as $productTranslation) {
                    $propertyName = $this->productDescriptionNameResolver->resolvePropertyName(
                        $productTranslation->getLocale()
                    );
                    $document->set($propertyName, $productTranslation->getDescription());
                }
            }
        );
    }
}

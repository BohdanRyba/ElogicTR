<?php
declare(strict_types=1);

namespace Bohdan\ProductScaner\Service;

use Bohdan\ProductScaner\Api\Terminal as TerminalInterface;
use Bohdan\ProductScaner\Service\Data\ProductRequest;
use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart;
use Magento\Framework\DataObject;
use Psr\Log\LoggerInterface;

/**
 *
 */
class Terminal implements TerminalInterface
{

    private ProductRepositoryInterface $productRepository;
    private LoggerInterface $logger;
    private Cart $cart;


    public function __construct(
        ProductRepositoryInterface $productRepository,
        LoggerInterface            $logger,
        Cart                       $cart
    ) {
        $this->productRepository = $productRepository;
        $this->logger = $logger;
        $this->cart = $cart;
    }


    /**
     * @param string $product
     * @param int $count
     * @return TerminalInterface
     */
    public function scan(string $product, int $count): TerminalInterface
    {
        try {
            $product = $this->productRepository->get($product);
            if ($this->isProductAvailable($product)) {

                $request = new DataObject([
                    'product' => $product->getId(),
                    'qty' => $count,
                ]);

                $this->cart
                    ->getQuote()
                    ->addProduct($product, $request);

            }

        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $this;
    }

    /**
     * @param $product
     * @return bool
     */
    private function isProductAvailable($product): bool
    {
        return $product->getId() && $product->isVisibleInCatalog();
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        $this->cart->save();
        return $this->cart->getQuote()->getGrandTotal();
    }
}

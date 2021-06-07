<?php


namespace Cardoso\MagentoSalesExtend\Plugin\Order;

use Magento\Sales\Api\Data\InvoiceExtensionFactory;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\InvoiceSearchResultInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;

/**
 * Class InvoiceRepositoryPlugin
 * @package Cardoso\MagentoSalesExtend\Plugin\Order
 */
class InvoiceRepositoryPlugin
{
    const FIELD_NAME = 'is_printed';

    /**
     * @var InvoiceExtensionFactory
     */
    protected $extensionFactory;

    /**
     * InvoiceRepositoryPlugin constructor.
     * @param InvoiceExtensionFactory $extensionFactory
     */
    public function __construct(InvoiceExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * Add "is_printed" extension attribute to invoice data object
     * @param InvoiceRepositoryInterface $subject
     * @param InvoiceInterface $invoice
     * @return InvoiceInterface
     */
    public function afterGet(InvoiceRepositoryInterface $subject, InvoiceInterface $invoice): InvoiceInterface
    {
        $printed = $invoice->getData(self::FIELD_NAME);
        $extensionAttributes = $invoice->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
        $extensionAttributes->setIsPrinted($printed);
        $invoice->setExtensionAttributes($extensionAttributes);
        return $invoice;
    }

    /**
     * Add "is_printed" extension attribute to order data object     *
     * @param InvoiceRepositoryInterface $subject
     * @param InvoiceSearchResultInterface $searchResult
     * @return InvoiceSearchResultInterface
     */
    public function afterGetList(InvoiceRepositoryInterface $subject, InvoiceSearchResultInterface $searchResult): InvoiceSearchResultInterface
    {
        $invoices = $searchResult->getItems();

        foreach ($invoices as &$invoice) {
            $printed = $invoice->getData(self::FIELD_NAME);
            $extensionAttributes = $invoice->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            $extensionAttributes->setIsPrinted($printed);
            $invoice->setExtensionAttributes($extensionAttributes);
        }
        return $searchResult;
    }
}

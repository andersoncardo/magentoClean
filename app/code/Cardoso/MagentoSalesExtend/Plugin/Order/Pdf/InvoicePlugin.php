<?php

namespace Cardoso\MagentoSalesExtend\Plugin\Order\Pdf;

use Magento\Sales\Api\Data\InvoiceExtensionFactory;
use Magento\Sales\Model\Order\InvoiceRepository;
use Magento\Sales\Model\Order\Pdf\Invoice;

/**
 * Class InvoicePlugin
 * @package Cardoso\MagentoSalesExtend\Plugin\Order\Pdf
 */
class InvoicePlugin
{
    /**
     * value default to printed invoices
     */
    const IS_PRINTED = 1 ;

    /**
     * @var InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * @var InvoiceExtensionFactory
     */
    protected $extensionFactory;

    /**
     * InvoicePlugin constructor.
     */
    public function __construct(
        InvoiceRepository $invoiceRepository,
        InvoiceExtensionFactory $invoiceExtensionFactory
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->extensionFactory = $invoiceExtensionFactory;
    }

    /**
     * @param Invoice $subject
     * @param $result
     * @param array $invoices
     * @return mixed
     */
    public function afterGetPdf(Invoice $subject, $result, array $invoices = []) {
        if (!$invoices) {
            return $result;
        }

        foreach ($invoices as $invoice) {
            if ($invoice->getIsPrinted() == self::IS_PRINTED)  {
                continue;
            }
            $invoice->setIsPrinted(self::IS_PRINTED);
            $this->invoiceRepository->save($invoice);
        }
        return $result;
    }
}

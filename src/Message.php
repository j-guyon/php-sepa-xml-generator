<?php

namespace SEPA;

/**
 * Class SepaMessage
 *
 * @package SEPA
 */
class Message extends XMLGenerator implements MessageInterface
{
    /**
     * @var$groupHeaderObjects GroupHeader
     */
    private $groupHeaderObjects;
    /**
     * @var $message \SimpleXMLElement
     */
    private $message;
    /**
     * @var $storeXmlPaymentsInfo \SimpleXMLElement
     */
    private $storeXmlPaymentsInfo;
    /**
     * @var array
     */
    private $paymentInfoObjects = [];

    public function __construct()
    {
        $this->createMessage();
        $this->storeXmlPaymentsInfo = new \SimpleXMLElement('<payments></payments>');
    }

    private function createMessage()
    {
        switch ($this->getDocumentPainMode()) {
            case self::PAIN_001_001_02:
            case self::PAIN_001_001_03: {
                    $documentMessage = "<CstmrCdtTrfInitn></CstmrCdtTrfInitn>";
                    break;
                }
            default: {
                    $documentMessage = "<CstmrDrctDbtInitn></CstmrDrctDbtInitn>";
                    break;
                }
        }

        $this->message = new \SimpleXMLElement($documentMessage);
    }

    /**
     * Add Group Header
     *
     * @param GroupHeader $groupHeaderObject
     * @return $this
     */
    public function setMessageGroupHeader(GroupHeader $groupHeaderObject)
    {
        if (is_null($this->groupHeaderObjects)) {
            $this->groupHeaderObjects = $groupHeaderObject;
        }

        return $this;
    }

    /**
     * @return GroupHeader
     */
    public function getMessageGroupHeader()
    {
        return $this->groupHeaderObjects;
    }

    /**
     * Add Message Payment Info
     *
     * @param PaymentInfo $paymentInfoObject
     * @return $this
     * @throws \Exception
     */
    public function addMessagePaymentInfo(PaymentInfo $paymentInfoObject)
    {
        if (!($paymentInfoObject instanceof PaymentInfo)) {
            throw new \Exception('Was not PaymentInfo Object in addMessagePaymentInfo');
        }

        $paymentInfoObject->resetNumberOfTransactions();
        $paymentInfoObject->resetControlSum();
        $this->paymentInfoObjects[$paymentInfoObject->getSequenceType()] = $paymentInfoObject;
        return $this;
    }

    /**
     * Get Payment Info Objects
     *
     * @return array
     */
    public function getPaymentInfoObjects()
    {
        return $this->paymentInfoObjects;
    }

    /**
     * Get Simple Xml Element Message
     *
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    public function getSimpleXMLElementMessage()
    {
        /**
         * @var $paymentInfo PaymentInfo
         */
        foreach ($this->paymentInfoObjects as $paymentInfo) {
            if (!$paymentInfo->checkIsValidPaymentInfo()) {
                throw new \Exception(ERROR_MSG_INVALID_PAYMENT_INFO . $paymentInfo->getPaymentInformationIdentification());
            }

            $paymentInfo->resetControlSum();
            $paymentInfo->resetNumberOfTransactions();

            $this->simpleXmlAppend($this->storeXmlPaymentsInfo, $paymentInfo->getSimpleXMLElementPaymentInfo());

            $this->getMessageGroupHeader()->setNumberOfTransactions($paymentInfo->getNumberOfTransactions());
            $this->getMessageGroupHeader()->setControlSum($paymentInfo->getControlSum());
        }

        $this->simpleXmlAppend($this->message, $this->getMessageGroupHeader()->getSimpleXmlGroupHeader());

        foreach ($this->storeXmlPaymentsInfo->children() as $element) {
            $this->simpleXmlAppend($this->message, $element);
        }

        return $this->message;
    }
}

<?php

namespace SEPA;

interface TransactionInterface
{
    public function checkIsValidTransaction();

    public function getSimpleXMLElementTransaction();

    public function getInstructedAmount();

    public function getInstructionIdentification();
}

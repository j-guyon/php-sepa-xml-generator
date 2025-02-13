<?php

//Error message for SepaGroupHeader class
define('ERROR_MSG_MESSAGE_IDENTIFICATION', 'SEPA Group Header Problem with MessageIdentification <MsgId>, Message Id is either missing or too big.');
define('ERROR_MSG_INITIATING_PARTY_NAME', 'SEPA Group Header Problem with InitiatingPartyName <InitgPty><Nm>.');
define('ERROR_MSG_PAYMENT_INFO_OBJECT_EMPTY', 'SEPA Group Header Payment Info Objects is empty, error raised while trying to obtain number of transactions.');
define('ERROR_MSG_NR_TRANSACTIONS', 'SEPA Group Header  error raised while trying to obtain number of transactions');
define('ERROR_MSG_PROBLEM_GENERATE_PM_INFO', 'Payment info cannot be generated. Payment Info ID: ');

//Error message for SepaPaymentInfo class
define('ERROR_MSG_PM_INFO_IDENTIFIER', 'SepaPaymentInfo: PaymentInformationIdentification <PmtInfId> is too big (max length=35).');
define('ERROR_MSG_PM_INFO_METHOD', 'SepaPaymentInfo: error on attribute PaymentMethod <PmtMtd> (max length=35).');
define('ERROR_MSG_CREDITOR_IBAN', 'SepaPaymentInfo: Creditor\'s IBAN is not valide.');
define('ERROR_MSG_DEBITOR_IBAN', 'SepaPaymentInfo: Debitor\'s IBAN is not valide.');
define('ERROR_MSG_CREDITOR_BIC', 'SepaPaymentInfo: Creditor\'s BIC <CdtrAgt><FinInstnId><BIC> is not valid.');
define('ERROR_MSG_DEBITOR_BIC', 'SepaPaymentInfo: Debitor\'s BIC <CdtrAgt><FinInstnId><BIC> is not valid.');
define('ERROR_MSG_CREDITOR_NAME', 'SepaPaymentInfo: Creditor\'s Name check it if is not empty.');
define('ERROR_MSG_DEBITOR_NAME', 'SepaPaymentInfo: Debitor\'s Name check it if is not empty.');
define('ERROR_MSG_PM_NR_TRANSACTIONS', 'SepaPaymentInfo: Payment info object doesn\'t have any transactions.');
define('ERROR_MSG_PM_BATCH_BOOKING', 'SepaPaymentInfo: Check if BATCH_BOOKING, it is empty');
define('ERROR_MSG_PM_CREDITOR_SCHEME_IDENTIFICATION', 'SepaPaymentInfo: Check if CREDITOR_SCHEME_IDENTIFICATION property, it is empty');
define('ERROR_MSG_PM_SEQUENCE_TYPE', 'SepaPaymentInfo: Check if SequenceType property is not empty');
define('ERROR_MSG_PM_METHOD_NOT_DEFINED', 'SepaPaymentInfo: Payment Method not defined');
define('ERROR_MSG_PM_ONLY_ONE_TYPE', 'SepaPaymentInfo: Payment Method can only have Credit Transfer or Direct Debit, but not both');

//Error messages for SepaDirectDebitTransactions class
define('ERROR_MSG_DD_CHECK_BIC', 'SepaDirectDebitTransactions: Debitor\'s BIC is not valid. Customer transaction Id : ');
define('ERROR_MSG_DD_NAME', 'SepaDirectDebitTransactions: Debitor\'s Name has invalid characters. Customer transaction Id : ');
define('ERROR_MSG_DD_IBAN', 'SepaDirectDebitTransactions: Debitor\'s IBAN is not valid. Customer transaction Id : ');
define('ERROR_MSG_DD_INVOICE_NUMBER', 'SepaDirectDebitTransactions: Error raised while setting the Invoice Number <Ustrd>. Customer transaction Id : ');
define('ERROR_MSG_INVALID_TRANSACTION', 'Invalid Transaction = ');
define('ERROR_MSG_INVALID_PAYMENT_INFO', 'Invalid Payment Info ID = ');

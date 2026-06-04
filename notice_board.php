<?php
$IsDue = false;  
$today = strtotime(date("Y/m/d"));
$invoiceList = $invoice->getInvoiceList();

foreach ($invoiceList as $invoice) {
    $originalOrderDate = $invoice['order_date'];
    $dueAmount = (float)$invoice['order_total_amount_due'];

    // Skip invoices where due amount is 0
    if ($dueAmount <= 0) {
        continue;
    }

    $customerId = $invoice['customer_id'];
    $invoiceId = $invoice['order_id'];
    $parsedDate = strtotime(date("Y-m-d", strtotime($originalOrderDate)));
    $difference = (int)(($today - $parsedDate) / 60 / 60 / 24);

    // Fetch customer details
    $customer = $ledger->getCustomer($customerId);

    // Check if customer data exists to prevent undefined variable issues
    if ($customer && isset($customer['customer_name'])) {
        echo '<li class="list-group-item alert-warning">Unpaid invoice of <b>' . $customer['customer_name'] . '</b>\'s invoice no. <b>' . $invoiceId . '</b> for ' . $difference . ' days. <b>Amount Due: ₹' . $dueAmount . '</b></li>';
        $IsDue = true;
    } else {
        echo '<li class="list-group-item alert-warning">Unpaid invoice of <b>Unknown Customer</b>\'s invoice no. <b>' . $invoiceId . '</b> for ' . $difference . ' days. <b>Amount Due: ₹' . $dueAmount . '</b></li>';
        $IsDue = true;
    }
}

// If no unpaid invoices are found,

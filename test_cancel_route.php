Route::get('/test-cancel-flow', function() {
    // Simulate what happens when SSLCommerz sends a cancel request
    $tranId = 'TEST_' . time();
    
    // Store some test data in session like a real transaction would
    session()->flash('payment_cancel_event_id', 1);
    session()->flash('payment_cancel_quantity', 2);
    session()->flash('payment_cancel_amount', 500.00);
    session()->flash('payment_cancel_tran_id', $tranId);
    
    return redirect()->route('payment.cancelled');
});

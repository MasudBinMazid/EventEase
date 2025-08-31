@extends('layouts.app')
@section('title','Ticket Verification')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold text-center mb-8">Ticket Verification</h1>
  
  <div class="bg-white shadow-lg rounded-xl p-6">
    <div class="mb-6">
      <label for="ticketCode" class="block text-sm font-medium text-gray-700 mb-2">
        Enter Ticket Code or Scan QR Code
      </label>
      <div class="flex gap-2">
        <input type="text" id="ticketCode" placeholder="e.g., TKT-ABC12345" 
               class="flex-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        <button onclick="verifyTicket()" 
                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold">
          Verify
        </button>
      </div>
    </div>

    <div id="scannerContainer" class="mb-6 text-center">
      <button onclick="startScanner()" id="scanBtn"
              class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
        📱 Start QR Scanner
      </button>
      <div id="reader" style="width: 100%; display: none;"></div>
    </div>

    <!-- Results area -->
    <div id="result" class="hidden">
      <div id="resultContent" class="p-4 rounded-lg"></div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
let html5QrcodeScanner = null;

function verifyTicket() {
  const ticketCode = document.getElementById('ticketCode').value.trim();
  if (!ticketCode) {
    alert('Please enter a ticket code');
    return;
  }

  fetch(`/verify/${ticketCode}`)
    .then(response => response.json())
    .then(data => displayResult(data))
    .catch(error => {
      console.error('Error:', error);
      displayError('Failed to verify ticket. Please try again.');
    });
}

function startScanner() {
  const readerDiv = document.getElementById('reader');
  const scanBtn = document.getElementById('scanBtn');
  
  if (readerDiv.style.display === 'none') {
    readerDiv.style.display = 'block';
    scanBtn.textContent = '❌ Stop Scanner';
    
    html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", 
      { fps: 10, qrbox: {width: 250, height: 250} },
      false
    );
    
    html5QrcodeScanner.render(
      (decodedText, decodedResult) => {
        try {
          // Check if it's compact format (ticket_code|user_id|event_id|status)
          if (decodedText.includes('|')) {
            const parts = decodedText.split('|');
            document.getElementById('ticketCode').value = parts[0]; // Use ticket code part
            verifyTicket();
            stopScanner();
          } else if (decodedText.startsWith('TKT-')) {
            // Simple ticket code format
            document.getElementById('ticketCode').value = decodedText;
            verifyTicket();
            stopScanner();
          } else {
            // Try to parse as JSON (legacy format)
            const qrData = JSON.parse(decodedText);
            if (qrData.ticket_code) {
              document.getElementById('ticketCode').value = qrData.ticket_code;
              verifyTicket();
              stopScanner();
            }
          }
        } catch (e) {
          // Fallback: treat as plain text
          document.getElementById('ticketCode').value = decodedText;
          verifyTicket();
          stopScanner();
        }
      },
      (error) => {
        // Ignore scan errors
      }
    );
  } else {
    stopScanner();
  }
}

function stopScanner() {
  if (html5QrcodeScanner) {
    html5QrcodeScanner.clear();
    html5QrcodeScanner = null;
  }
  document.getElementById('reader').style.display = 'none';
  document.getElementById('scanBtn').textContent = '📱 Start QR Scanner';
}

function displayResult(data) {
  const resultDiv = document.getElementById('result');
  const contentDiv = document.getElementById('resultContent');
  
  resultDiv.classList.remove('hidden');
  
  if (data.valid) {
    contentDiv.className = 'p-4 rounded-lg bg-green-50 border border-green-200';
    contentDiv.innerHTML = `
      <div class="flex items-center mb-3">
        <div class="text-green-600 text-xl mr-2">✅</div>
        <h3 class="text-lg font-semibold text-green-800">Valid Ticket</h3>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
        <div><strong>Event:</strong> ${data.data.event_title}</div>
        <div><strong>Date:</strong> ${data.data.event_date}</div>
        <div><strong>Venue:</strong> ${data.data.venue}</div>
        <div><strong>Holder:</strong> ${data.data.holder_name}</div>
        <div><strong>Email:</strong> ${data.data.holder_email}</div>
        <div><strong>Quantity:</strong> ${data.data.quantity}</div>
        <div><strong>Amount:</strong> $${data.data.total_amount}</div>
        <div><strong>Issued:</strong> ${data.data.issued_at}</div>
      </div>
    `;
  } else {
    contentDiv.className = 'p-4 rounded-lg bg-red-50 border border-red-200';
    contentDiv.innerHTML = `
      <div class="flex items-center mb-3">
        <div class="text-red-600 text-xl mr-2">❌</div>
        <h3 class="text-lg font-semibold text-red-800">Invalid Ticket</h3>
      </div>
      <p class="text-red-700">${data.message}</p>
      ${data.data ? `
        <div class="mt-3 text-sm text-gray-600">
          <div><strong>Ticket Code:</strong> ${data.data.ticket_code}</div>
          <div><strong>Status:</strong> ${data.status}</div>
        </div>
      ` : ''}
    `;
  }
}

function displayError(message) {
  const resultDiv = document.getElementById('result');
  const contentDiv = document.getElementById('resultContent');
  
  resultDiv.classList.remove('hidden');
  contentDiv.className = 'p-4 rounded-lg bg-red-50 border border-red-200';
  contentDiv.innerHTML = `
    <div class="flex items-center">
      <div class="text-red-600 text-xl mr-2">⚠️</div>
      <p class="text-red-700">${message}</p>
    </div>
  `;
}

// Allow Enter key to verify
document.getElementById('ticketCode').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    verifyTicket();
  }
});
</script>
@endsection

@extends('layouts.app')
@section('title','Ticket Verification')

@section('extra-css')
<style>
/* Mobile-friendly QR scanner styles */
#reader {
  border: 2px solid #10b981;
  border-radius: 12px;
  overflow: hidden;
  background: #000;
}

#reader video {
  border-radius: 8px;
}

/* Make scanner responsive */
@media (max-width: 640px) {
  #reader {
    width: 100% !important;
    max-width: 100% !important;
  }
  
  #reader video {
    width: 100% !important;
    height: auto !important;
  }
  
  .max-w-2xl {
    padding: 0 1rem;
  }
}

/* Scanner overlay styling */
#reader canvas, #reader video {
  max-width: 100% !important;
  height: auto !important;
}

/* Instructions styling */
.scanner-instructions {
  font-size: 0.875rem;
  line-height: 1.25rem;
}
</style>
@endsection

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
               class="flex-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
        <button onclick="verifyTicket()" 
                class="px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 font-semibold">
          Verify
        </button>
      </div>
    </div>

    <div id="scannerContainer" class="mb-6 text-center">
      <button onclick="startScanner()" id="scanBtn"
              class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
        📱 Start QR Scanner
      </button>
      
      <!-- Camera instructions -->
      <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
        <div class="font-semibold mb-1">📋 Scanner Instructions:</div>
        <ul class="text-left space-y-1">
          <li>• Allow camera permission when prompted</li>
          <li>• Hold your phone steady and point at the QR code</li>
          <li>• Ensure good lighting for best results</li>
          <li>• The scanner will automatically detect and verify the ticket</li>
        </ul>
      </div>
      
      <!-- Scanner area -->
      <div id="reader" style="width: 100%; display: none; margin-top: 1rem;">
        <!-- Scanner will be injected here -->
      </div>
      
      <!-- Loading/Error messages -->
      <div id="scannerStatus" class="mt-3 text-sm" style="display: none;"></div>
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

async function startScanner() {
  const readerDiv = document.getElementById('reader');
  const scanBtn = document.getElementById('scanBtn');
  
  if (readerDiv.style.display === 'none') {
    try {
      // Check if camera is available first
      if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        displayError('Camera not supported on this device/browser.');
        return;
      }

      // Request camera permission first
      scanBtn.textContent = '🔄 Starting Camera...';
      showScannerStatus('Requesting camera permission...', 'info');
      
      // Test camera access
      const stream = await navigator.mediaDevices.getUserMedia({ 
        video: { 
          facingMode: { ideal: "environment" }, // Prefer back camera
          width: { ideal: 1280 },
          height: { ideal: 720 }
        } 
      });
      
      // Stop the test stream immediately
      stream.getTracks().forEach(track => track.stop());
      
      // Now start the QR scanner
      readerDiv.style.display = 'block';
      scanBtn.textContent = '❌ Stop Scanner';
      showScannerStatus('Camera ready! Point at QR code to scan', 'success');
      
      // Enhanced scanner configuration
      const config = {
        fps: 10,
        qrbox: function(viewfinderWidth, viewfinderHeight) {
          // Square QR box that's responsive
          let minEdgePercentage = 0.7;
          let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
          let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
          return {
            width: qrboxSize,
            height: qrboxSize
          };
        },
        aspectRatio: 1.0,
        disableFlip: false, // Allow both cameras
        // Prefer environment camera (back camera on mobile)
        videoConstraints: {
          facingMode: { ideal: "environment" }
        }
      };
      
      html5QrcodeScanner = new Html5QrcodeScanner("reader", config, false);
      
      html5QrcodeScanner.render(
        (decodedText, decodedResult) => {
          try {
            showScannerStatus('QR Code detected! Verifying...', 'info');
            
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
          // Only log errors, don't display them to avoid spam
          console.log('QR scan error (normal):', error);
        }
      );
      
    } catch (error) {
      console.error('Camera access error:', error);
      scanBtn.textContent = '📱 Start QR Scanner';
      hideScannerStatus();
      
      if (error.name === 'NotAllowedError') {
        displayError('Camera permission denied. Please allow camera access and try again.');
      } else if (error.name === 'NotFoundError') {
        displayError('No camera found on this device.');
      } else if (error.name === 'NotSupportedError') {
        displayError('Camera not supported in this browser. Try Chrome, Firefox, or Safari.');
      } else {
        displayError('Failed to start camera: ' + error.message + '. Please try again.');
      }
    }
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
  hideScannerStatus();
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
        ${data.data.ticket_type ? `<div><strong>Ticket Type:</strong> ${data.data.ticket_type}</div>` : ''}
        <div><strong>Quantity:</strong> ${data.data.quantity}</div>
        <div><strong>Unit Price:</strong> ৳${data.data.unit_price}</div>
        <div><strong>Total Amount:</strong> ৳${data.data.total_amount}</div>
        <div><strong>Issued:</strong> ${data.data.issued_at}</div>
        ${data.data.ticket_type_description ? `<div class="md:col-span-2"><strong>Description:</strong> ${data.data.ticket_type_description}</div>` : ''}
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
  const statusDiv = document.getElementById('scannerStatus');
  
  // Show in results area
  resultDiv.classList.remove('hidden');
  contentDiv.className = 'p-4 rounded-lg bg-red-50 border border-red-200';
  contentDiv.innerHTML = `
    <div class="flex items-center">
      <div class="text-red-600 text-xl mr-2">⚠️</div>
      <p class="text-red-700">${message}</p>
    </div>
  `;
  
  // Also show in scanner status area if visible
  if (statusDiv.style.display !== 'none') {
    statusDiv.innerHTML = `<div class="text-red-600">❌ ${message}</div>`;
    statusDiv.style.display = 'block';
  }
}

function showScannerStatus(message, type = 'info') {
  const statusDiv = document.getElementById('scannerStatus');
  let icon = '🔄';
  let className = 'text-blue-600';
  
  if (type === 'error') {
    icon = '❌';
    className = 'text-red-600';
  } else if (type === 'success') {
    icon = '✅';
    className = 'text-green-600';
  }
  
  statusDiv.innerHTML = `<div class="${className}">${icon} ${message}</div>`;
  statusDiv.style.display = 'block';
}

function hideScannerStatus() {
  const statusDiv = document.getElementById('scannerStatus');
  statusDiv.style.display = 'none';
}

// Allow Enter key to verify
document.getElementById('ticketCode').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    verifyTicket();
  }
});

// Add some mobile-specific handling
document.addEventListener('DOMContentLoaded', function() {
  // Add orientation change handler for mobile
  if (window.orientation !== undefined) {
    window.addEventListener('orientationchange', function() {
      // Stop and restart scanner on orientation change to fix layout
      if (html5QrcodeScanner && document.getElementById('reader').style.display !== 'none') {
        setTimeout(() => {
          stopScanner();
          setTimeout(() => startScanner(), 500);
        }, 500);
      }
    });
  }
});
</script>
@endsection

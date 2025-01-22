<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>
<h1>Scan QR Code</h1>

<!-- The container for QR code scanning -->
<div id="reader" style="width: 600px; height: 400px;"></div>

<button id="stop-scanning" style="display: none;">Stop Scanning</button>

<script>
    // Initialize the scanner
    const html5QrCode = new Html5Qrcode("reader");

    const startScanning = () => {
        html5QrCode.start(
            { facingMode: "environment" },  // Use back camera
            {
                fps: 10, // frames per second
                qrbox: { width: 250, height: 250 }, // scanning box size
            },
            (decodedText, decodedResult) => {

                // Send the QR code data to the Laravel API
                fetch('/api/scan-qr', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        qr_code_data: decodedText,
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

                // // Stop scanning after successful scan
                // html5QrCode.stop().then(() => {
                //     document.getElementById('stop-scanning').style.display = 'none';
                // }).catch(err => {
                //     console.error('Failed to stop scanning.', err);
                // });
            },
            (errorMessage) => {
                // Error handling for QR code scanning
                // console.error("QR Code scan error:", errorMessage);
            }
        ).catch(err => {
            console.error('Failed to start scanning:', err);
        });

        // Show the stop button when scanning starts
        document.getElementById('stop-scanning').style.display = 'block';
    };

    // Start scanning when the page loads
    window.onload = startScanning;

    // Stop scanning manually
    document.getElementById('stop-scanning').onclick = () => {
        html5QrCode.stop().then(() => {
            document.getElementById('stop-scanning').style.display = 'none';
        }).catch(err => {
            console.error('Failed to stop scanning.', err);
        });
    };
</script>
</body>
</html>

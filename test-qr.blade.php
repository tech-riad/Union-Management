<!DOCTYPE html>
<html>
<head>
    <title>Test QR Code</title>
</head>
<body>
    <h1>Test QR Code Display</h1>
    
    <h2>Base64 Image:</h2>
    <img src="{{ $qrCode }}" alt="QR Code" width="200" height="200">
    
    <h2>Raw QR Code:</h2>
    {!! QrCode::size(200)->generate('https://localhost:8000/verify/CERT-2025-00125') !!}
    
    <h2>Debug Info:</h2>
    <p>QR Code String Length: {{ strlen($qrCode) }}</p>
    <p>Starts with: {{ substr($qrCode, 0, 50) }}...</p>
</body>
</html>
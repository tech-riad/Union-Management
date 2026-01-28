<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .error-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
            text-align: center;
        }
        .error-header {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            color: white;
            padding: 40px 20px;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            line-height: 1;
            margin: 0;
        }
        .error-title {
            font-size: 24px;
            margin: 10px 0 0;
            font-weight: 500;
        }
        .error-content {
            padding: 40px;
        }
        .error-message {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        .btn {
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e9ecef;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .error-footer {
            padding: 20px;
            background: #f8f9fa;
            color: #666;
            font-size: 14px;
        }
        .home-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-header">
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Page Not Found</h2>
        </div>
        
        <div class="error-content">
            <div class="error-message">
                <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                <p>Please check the URL and try again.</p>
            </div>
            
            <div class="error-actions">
                <a href="{{ url('/') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Go Home
                </a>
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Go Back
                </a>
            </div>
            
            <a href="{{ url('/') }}" class="home-link">
                <i class="fas fa-arrow-circle-left"></i>
                Return to Homepage
            </a>
        </div>
        
        <div class="error-footer">
            <p>© {{ date('Y') }} Union Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Access Error</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, 'Fira Sans', 'Droid Sans', sans-serif;
            background: linear-gradient(145deg, #1a2a6c 0%, #b21f1f 50%, #fdbb2d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }
        
        .error-container {
            max-width: 700px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 30px;
            padding: 50px 40px;
            box-shadow: 0 40px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .error-icon {
            font-size: 90px;
            line-height: 1;
            margin-bottom: 25px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        h1 {
            font-size: 48px;
            color: #1a2a6c;
            margin-bottom: 15px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .error-code {
            font-size: 20px;
            color: #b21f1f;
            font-weight: 600;
            margin-bottom: 20px;
            background: rgba(178, 31, 31, 0.1);
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
        }
        
        .message-box {
            background: #f8f9fa;
            border-left: 5px solid #fdbb2d;
            padding: 25px;
            border-radius: 15px;
            margin: 30px 0;
            text-align: left;
        }
        
        .message-box p {
            margin: 12px 0;
            color: #2c3e50;
            font-size: 16px;
            line-height: 1.6;
        }
        
        .url-display {
            background: #e9ecef;
            padding: 12px 15px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            word-break: break-all;
            margin: 15px 0;
            color: #495057;
            border: 1px solid #dee2e6;
        }
        
        .method-badge {
            display: inline-block;
            background: #b21f1f;
            color: white;
            padding: 6px 15px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            margin: 10px 0;
            text-transform: uppercase;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .info-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .info-card h3 {
            color: #1a2a6c;
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .info-card p {
            color: #6c757d;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .info-card .icon {
            font-size: 30px;
            margin-bottom: 10px;
            display: block;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .btn {
            padding: 14px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(145deg, #1a2a6c, #2a3a7c);
            color: white;
            box-shadow: 0 5px 15px rgba(26, 42, 108, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(145deg, #2a3a7c, #1a2a6c);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 42, 108, 0.4);
        }
        
        .btn-secondary {
            background: white;
            color: #1a2a6c;
            border: 2px solid #1a2a6c;
        }
        
        .btn-secondary:hover {
            background: #1a2a6c;
            color: white;
            transform: translateY(-2px);
        }
        
        .footer-note {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        
        .footer-note a {
            color: #1a2a6c;
            text-decoration: none;
            font-weight: 600;
        }
        
        .footer-note a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 600px) {
            .error-container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 36px;
            }
            
            .error-icon {
                font-size: 70px;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">🔒</div>
        <h1>API Access Restricted</h1>
        <div class="error-code">Error 405 - Method Not Allowed</div>
        
        <div class="method-badge">{{ $method }} Request Detected</div>
        
        <div class="message-box">
            <p><strong>This URL is part of our API system and cannot be accessed directly through a browser.</strong></p>
            <p>Our API endpoints are designed exclusively for mobile application communication and require specific request methods and headers.</p>
        </div>
        
        <div class="url-display">
            <strong>Requested URL:</strong> {{ $url }}
        </div>
        
        <div class="info-grid">
            <div class="info-card">
                <span class="icon">📱</span>
                <h3>Mobile App Only</h3>
                <p>This API is exclusively for use with our official mobile application.</p>
            </div>
            
            <div class="info-card">
                <span class="icon">🔐</span>
                <h3>Secure Endpoint</h3>
                <p>API endpoints require proper authentication and specific request methods (POST, PUT, DELETE).</p>
            </div>
            
            <div class="info-card">
                <span class="icon">📨</span>
                <h3>Wrong Method</h3>
                <p>You're using a GET request, but this endpoint expects POST or other methods.</p>
            </div>
        </div>
        
        <div class="btn-group">
            <a href="/" class="btn btn-primary">
                <span>🏠</span> Return to Homepage
            </a>
            <a href="/contact" class="btn btn-secondary">
                <span>📞</span> Contact Support
            </a>
        </div>
        
        <div class="footer-note">
            <p>If you're a developer integrating with our API, please ensure you're using the correct HTTP methods and headers.<br>
            Need help? <a href="/docs/api">View API Documentation</a> or <a href="/support">Contact Support</a></p>
        </div>
    </div>
</body>
</html>
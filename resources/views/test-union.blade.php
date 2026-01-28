<!DOCTYPE html>
<html>
<head>
    <title>Test UnionHelper</title>
</head>
<body>
    <h1>UnionHelper Test</h1>
    
    <p>Primary Color: <span style="color: {{ \App\Helpers\UnionHelper::getPrimaryColor() }}">{{ \App\Helpers\UnionHelper::getPrimaryColor() }}</span></p>
    <p>Secondary Color: <span style="color: {{ \App\Helpers\UnionHelper::getSecondaryColor() }}">{{ \App\Helpers\UnionHelper::getSecondaryColor() }}</span></p>
    <p>Union Name: {{ \App\Helpers\UnionHelper::getName() }}</p>
    <p>Contact: {{ \App\Helpers\UnionHelper::getContactNumber() }}</p>
    
    <div style="display: flex; gap: 10px; margin: 20px 0;">
        <div style="width: 50px; height: 50px; background: {{ \App\Helpers\UnionHelper::getPrimaryColor() }};"></div>
        <div style="width: 50px; height: 50px; background: {{ \App\Helpers\UnionHelper::adjustColor(\App\Helpers\UnionHelper::getPrimaryColor(), -20) }};"></div>
        <div style="width: 50px; height: 50px; background: {{ \App\Helpers\UnionHelper::adjustColor(\App\Helpers\UnionHelper::getPrimaryColor(), 40) }};"></div>
    </div>
    
    <h2>CSS Variables:</h2>
    <p>--primary: {{ \App\Helpers\UnionHelper::getPrimaryColor() }}</p>
    <p>--primary-rgb: {{ \App\Helpers\UnionHelper::hexToRgb(\App\Helpers\UnionHelper::getPrimaryColor()) }}</p>
</body>
</html>
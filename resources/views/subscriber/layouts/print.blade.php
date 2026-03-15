<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة - @yield('title')</title>
    <!-- Use a clean font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: white;
            color: #333;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .print-container {
            max-width: 800px;
            margin: 0 auto;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        header .store-info h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        header .store-info p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .document-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .document-title h2 {
            display: inline-block;
            border-bottom: 3px solid #333;
            padding-bottom: 5px;
            margin: 0;
            text-transform: uppercase;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
        }
        .meta-info div p {
            margin: 5px 0;
            font-size: 14px;
        }
        .meta-info div p strong {
            display: inline-block;
            width: 100px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th, table td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: right;
        }
        table th {
            background-color: #f5f5f5;
            font-size: 14px;
        }
        table td {
            font-size: 13px;
        }
        .totals {
            margin-right: auto;
            width: 300px;
        }
        .totals p {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            margin: 0;
        }
        .totals p.grand-total {
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #333;
        }
        footer {
            margin-top: 50px;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 20px;
            color: #999;
            font-size: 12px;
        }

        @media print {
            body { padding: 0; }
            .print-btn { display: none; }
            @page { margin: 1cm; }
        }
        
        .print-btn-container {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
        }
        .btn {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: inherit;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="print-btn-container">
        <button onclick="window.print()" class="btn print-btn">طباعة المستند</button>
        <button onclick="window.close()" class="btn print-btn" style="background: #6c757d;">إغلاق</button>
    </div>

    <div class="print-container">
        @yield('document_content')
        
        <footer>
            تم إنشاء هذا المستند عبر نظام دكان - المنصة السحابية لإدارة التجارة والمخزون
        </footer>
    </div>
</body>
</html>

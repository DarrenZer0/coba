<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Indomaret</title>
    <style>
    body {
        margin: 0;
        font-family: "Inter", Arial, sans-serif;
        background: #f5f7fa;
        color: #333;
    }

    header {
        text-align: center;
        padding: 40px 20px 20px;
    }

    h1 {
        margin: 0 0 20px;
        font-size: 28px;
        font-weight: 600;
        color: #222;
    }

    nav {
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        width: fit-content;
        margin: 0 auto;
        padding: 10px 20px;
    }

    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        gap: 14px;
    }

    nav ul li a {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
        padding: 10px 18px;
        border-radius: 8px;
        transition: all 0.25s ease;
    }

    nav ul li a:hover {
        background: #007bff;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
    }
    </style>
</head>

<body>
    <header>
        <h1>Aplikasi Indomaret (Point Of Sales)</h1>
        <nav>
            <ul>
                <li><a href="/indomaret/pages/dashboard.php">Dashboard</a></li>
                <li><a href="/indomaret/pages/cashiers/list.php">Kasir</a></li>
                <li><a href="/indomaret/pages/products/list.php">Produk</a></li>
                <li><a href="/indomaret/pages/transactions/list.php">Transaksi</a></li>
            </ul>
        </nav>
    </header>
    <main>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Indomaret</title>
    <style>
    nav {
        background: #007bff;
        padding: 10px 0;
    }

    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        gap: 20px;
        justify-content: center;
    }

    nav ul li {
        display: inline;
    }

    nav ul li a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        padding: 8px 16px;
        border-radius: 4px;
        transition: background 0.2s;
    }

    nav ul li a:hover {
        background: #0056b3;
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
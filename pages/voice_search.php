<?php
include '../includes/db.php';

// Handle search query if user types or uses voice
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if ($searchTerm != '') {
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
    $stmt->execute(["%$searchTerm%"]);
} else {
    $stmt = $conn->query("SELECT * FROM products");
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voice Search Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .voice-search-container {
            text-align: center;
            margin: 20px 0;
        }
        #searchBox {
            width: 50%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        #micBtn {
            padding: 10px 15px;
            font-size: 18px;
            margin-left: 10px;
            cursor: pointer;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        #micBtn:hover {
            background-color: #218838;
        }
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .product-card {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .product-card h3 {
            margin: 5px 0;
        }
        .product-card p {
            color: #555;
            font-size: 14px;
        }
        .product-card span {
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Search Products with Voice</h2>

    <form id="searchForm" method="GET" action="">
        <div class="voice-search-container">
            <input type="text" id="searchBox" name="search" placeholder="Search products..." value="<?= htmlspecialchars($searchTerm); ?>">
            <button type="button" id="micBtn" title="Click and speak">🎤</button>
        </div>
    </form>

    <div class="products">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                    <h3><?= htmlspecialchars($product['name']); ?></h3>
                    <p><?= htmlspecialchars($product['description']); ?></p>
                    <span>$<?= number_format($product['price'], 2); ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; width:100%;">No products found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
const searchBox = document.getElementById('searchBox');
const micBtn = document.getElementById('micBtn');

const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
recognition.lang = 'en-US';

micBtn.addEventListener('click', () => {
    recognition.start();
    micBtn.textContent = "🎙 Listening...";
});

recognition.onresult = (event) => {
    const transcript = event.results[0][0].transcript;
    searchBox.value = transcript;
    micBtn.textContent = "🎤";

    // Automatically submit the form after voice input
    document.getElementById('searchForm').submit();
};

recognition.onspeechend = () => {
    recognition.stop();
    micBtn.textContent = "🎤";
};

recognition.onerror = (event) => {
    console.error(event.error);
    micBtn.textContent = "🎤";
};
</script>

</body>
</html>
<?php 
    session_start();
    include 'connection.php';

    if($_SESSION['role'] != 'admin'){
        header("Location: ../index.php");
        exit();
    }

    $trade_statuses = ['Pending', 'Ongoing', 'Completed', 'Declined'];
    $trade_data = [];

    foreach ($trade_statuses as $status) {
        $stmt = $con->prepare("
            SELECT t.*, p.Product_Image 
            FROM trade t
            LEFT JOIN products p ON t.Product_ID = p.Product_ID
            WHERE t.Trade_Status = ?
            ORDER BY t.Trade_ID DESC;
        ");
        $stmt->bind_param('s', $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $trade_data[$status] = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $stmt = $con->prepare("UPDATE trade SET Trade_Status = ? WHERE Trade_ID = ?");
        $stmt->bind_param('si', $_POST['update'], $_POST['trade_id']);
        $stmt->execute();
        $stmt->close();
        $con->close();

        header('location:' . $_SERVER['PHP_SELF'] . '?status_updated=1');
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Changa+One:ital@0;1&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Climate+Crisis&display=swap" rel="stylesheet">
    <title>Pending Trades</title>
    <link rel="stylesheet" href="../css/pending_trades.css">
    <link rel="shortcut icon" href="../images/PoCaSwap Logo.ico"/>
</head>
<body>
    <div class="header">
        <div class="logo">
        <a href="../index.php">
            <img src="../images/PoCaSwap Logo.png" alt="Logo">
        </a>
            <div class="title">
                <span>PoCaSwap</span>
            </div>
        </div>
        <div class="nav">
            <a href="dashboard.php">Home</a>
            <a href="products.php">Products</a>
            <a href="orders.php">Pending Orders</a>
            <a href="pending_trades.php">Pending Trades</a>
        </div>
        </div>
</head>
<body>
    <h2>Pending Trades</h2>
    <div class="status">
        <?php foreach ($trade_statuses as $status) : ?>
            <button id="<?= strtolower($status) ?>" onclick="showTab('<?= htmlspecialchars($status, ENT_QUOTES) ?>')">
                <?= htmlspecialchars($status) ?> (<?= count($trade_data[$status] ?? []) ?>)
            </button>
        <?php endforeach; ?>
    </div>


    <?php foreach($trade_statuses as $status) : ?>
        <div id="<?= htmlspecialchars($status, ENT_QUOTES) ?>" class="tab-content">
            <?php if(empty($trade_data[$status])) : ?>
                <p>No <?=$status?> Trades</p>
            <?php else: ?>
            <table>
                <tr>
                    <th>Trade ID</th>
                    <th>Username</th>
                    <th>Product ID</th>
                    <th>Product Image</th>
                    <th>Trade Name</th>
                    <th>Description</th>
                    <th>Trade Offer</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
                <?php foreach($trade_data[$status] as $trade) : ?>
                <tr class="trade-row" data-status="<?= htmlspecialchars($trade['Trade_Status']) ?>">
                    <td><?= htmlspecialchars($trade['Trade_ID']) ?></td>
                    <td><?= htmlspecialchars($trade['username']) ?></td>
                    <td><?= htmlspecialchars($trade['Product_ID']) ?></td>
                    
                    <!-- Product Image -->
                    <td>
                        <?php
                        $productPath = "../products/" . $trade['Product_Image'];
                        if (!empty($trade['Product_Image']) && file_exists($productPath)) {
                            echo '<img src="' . htmlspecialchars($productPath) . '" alt="Product Image" width="50">';
                        } else {
                            echo '<img src="../images/default.png" alt="No Image" width="50">';
                        }
                        ?>
                    </td>


                    <td><?= htmlspecialchars($trade['Trade_Name']) ?></td>
                    <td><?= htmlspecialchars($trade['Trade_Description']) ?></td>

                    <!-- Trade Offer Image -->
                    <td>
                        <?php
                        $offerPath = "../trades/" . $trade['Trade_Offer'];
                        if (!empty($trade['Trade_Offer']) && file_exists($offerPath)) {
                            echo '<img src="' . htmlspecialchars($offerPath) . '" alt="Trade Offer" width="50">';
                        } else {
                            echo '<img src="../images/default.png" alt="No Trade Offer" width="50">';
                        }
                        ?>
                    </td>

                    <td>
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                            <select name="update">
                                <?php foreach($trade_statuses as $opt) :?>    
                                    <option value="<?= htmlspecialchars($opt) ?>" <?= $trade['Trade_Status'] == $opt ? 'selected' : "" ?>>
                                        <?= htmlspecialchars($opt) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="trade_id" value="<?= htmlspecialchars($trade['Trade_ID']) ?>">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($trade['Trade_Status']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <script>
        function showTab(status) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active-tab');
            });
            document.getElementById(status).classList.add('active-tab');
        }
        showTab('Pending');
    </script>
</body>
</html>

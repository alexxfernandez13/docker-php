<?php

require_once("config.php");

session_start();

if (!isset($_SESSION["username"])) {
    header("location:index.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Introduction to PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    <style>
        /* Estilo adicional para forzar la visualización horizontal */
        .container {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 20px;
            overflow: hidden;
        }

        .card {
            width: 18rem;
            margin: 10px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .card {
                width: 15rem;
            }
        }

        /* Evitar el desbordamiento horizontal */
        body {
            overflow-x: hidden;
        }
    </style>
</head>

<body>
    <div class="container">
        <span>Welcome to my app!</span>
    </div>
    <div class="container">
        <?php
        $query = "SELECT * FROM users";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">'.$row["username"].'</h5>
                        <h6 class="card-subtitle mb-2 text-muted">'.$row["id"].'</h6>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the cards content.</p>
                        <a href="#" class="card-link">Card link</a>
                        <a href="#" class="card-link">Another link</a>
                    </div>
                </div>';
            }
        }
        ?>
    </div>
    <div class="container">
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
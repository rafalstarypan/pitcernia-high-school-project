<?php

	session_start();
	
	if (!isset($_SESSION['logged_in']))
	{
		header('Location: login_page.php');
		exit();
	}
	if (isset($_SESSION['logged_in']) && $_SESSION['is_worker']) {
		header('Location: admin_panel.php');
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona główna</title>
    <link rel="stylesheet" type="text/css" href="styles/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    
</head>
<body>
         <?php
         
            require_once "connect.php";
            $idUser = $_SESSION['id_user'];
            $sql = "SELECT * FROM ordering WHERE user_id = $idUser ORDER BY date DESC;";
            $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
            $conn->set_charset('utf8');
            $result = $conn->query($sql);
        ?>

    <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Pitcernia</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse flex-row-reverse" id="navbarNavDropdown">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="menu.php">Strona główna<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="order.php">Zamów<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Twój profil
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="history.php">Historia zamówień</a>
                <a class="dropdown-item" href="profile.php">Profil</a>
                <a class="dropdown-item" href="logout.php">Wyloguj</a>
                </div>
            </li>
            </ul>
        </div>
        </nav>
        
        <h1 class="text-center myHeader">Historia zamówień</h1>

        <div class="container">

        <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Produkty</th>
      <th scope="col">Łaczny koszt</th>
      <th scope="col">Data</th>
    </tr>
  </thead>
  <tbody>
  <?php

    while ($row = $result->fetch_assoc()) {
        $orderingId = $row['id_ordering'];

        // get products data
		$sql = "SELECT * FROM ordering_menu WHERE ordering_id = $orderingId;";
		$products = $conn->query($sql);
        echo "<tr>";
        echo "<td>";
			while ($pro = $products->fetch_assoc()) {
				$menuId = $pro['menu_id'];
				$sql = "SELECT name FROM menu WHERE id_menu = $menuId;";
				$item = $conn->query($sql);
				$item = $item->fetch_assoc();
				echo "<p>" . $pro['amount'] . " x " . $item['name'] . "</p>";
			}

		echo "</td>";
        echo "<td>" . $row['total_price'] . "</td>";
        echo "<td>" . $row['date'] . "</td>";
        echo "</tr>";
    }

    ?>
  </tbody>
</table>

</div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
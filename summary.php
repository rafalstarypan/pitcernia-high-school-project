<?php

	session_start();
	
	if (!isset($_SESSION['logged_in']) || !$_SESSION['is_worker'])
	{
		header('Location: login_page.php');
		exit();
	}
    
    function printTable($result) {
        $products = [];
        $totalMoney = 0;

        while ($row = $result->fetch_assoc()) {
            $name = $row['name'];
            $amount = $row['amount'];
            $totalMoney += $row['price'] * $row['amount'];
            @$products[$name] += $amount;
        }

        foreach ($products as $name => $cnt) {
            echo "<tr>";
            echo "<td>" . $name . "</td>";
            echo "<td>" . $cnt . "</td>";
            echo "</tr>";
        }
        echo "<tr colspan='2'><td></td><td>Zarobiliśmy: " . $totalMoney . "</td></tr>";
    }

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Podsumowania</title>
	<link rel="stylesheet" type="text/css" href="styles/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>

<body>
	
	<?php

        require_once "connect.php";
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        $conn->set_charset('utf8');
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
                <a class="nav-link" href="admin_panel.php">Historia zamówień<span class="sr-only">(current)</span></a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Twój profil
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
					<a class="dropdown-item" href="summary.php">Podsumowania</a>
                	<a class="dropdown-item" href="logout.php">Wyloguj</a>
                </div>
            </li>
            </ul>
        </div>
        </nav>

	<h1 class="text-center myHeader">Podsumowania</h1>

	<div class="container">

        <!-- Today table -->
        <h3 class="smallHeader">Dzisiaj</h3>
        <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Product</th>
            <th scope="col">Ilość</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $sql = "SELECT * FROM `ordering` JOIN `ordering_menu` ON id_ordering = ordering_id JOIN `menu` ON id_menu = menu_id WHERE DAY(date) = DAY(NOW()) AND MONTH(date) = MONTH(NOW()) AND YEAR(date) = YEAR(NOW());";
            $result = $conn->query($sql);
            printTable($result);
        ?>
        </tbody>
        </table>

        <!-- Week table -->
        <h3 class="smallHeader">W tygodniu</h3>
        <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Product</th>
            <th scope="col">Ilość</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $sql = "SELECT * FROM `ordering` JOIN `ordering_menu` ON id_ordering = ordering_id JOIN `menu` ON id_menu = menu_id WHERE WEEK(date) = WEEK(NOW()) AND MONTH(date) = MONTH(NOW()) AND YEAR(date) = YEAR(NOW());";
            $result = $conn->query($sql);
            printTable($result);
        ?>
        </tbody>
        </table>

        <!-- Month table -->
        <h3 class="smallHeader">W miesiącu</h3>
        <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Product</th>
            <th scope="col">Ilość</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $sql = "SELECT * FROM `ordering` JOIN `ordering_menu` ON id_ordering = ordering_id JOIN `menu` ON id_menu = menu_id WHERE MONTH(date) = MONTH(NOW()) AND YEAR(date) = YEAR(NOW());";
            $result = $conn->query($sql);
            printTable($result);
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
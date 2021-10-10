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
    <title>Profil użytkownika</title>
    <link rel="stylesheet" type="text/css" href="styles/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    
</head>
<body>
         <?php
         
            require_once "connect.php";
            $userId = $_SESSION['id_user'];
            $sql = "SELECT * FROM user WHERE id_user = $userId;";
            $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
            $conn->set_charset('utf8');
            $result = $conn->query($sql);
            $result = $result->fetch_assoc();
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
        
        <h1 class="text-center myHeader">Profil użytkownika</h1>

        <div class="container">

        <table class="table table-striped">

            <tbody>
            <?php
                echo "<tr><td>Login: </td>" . "<td>" . $result['login'] . "</td></tr>"; 
                echo "<tr><td>Imię: </td>" . "<td>" . $result['firstname'] . "</td></tr>"; 
                echo "<tr><td>Nazwisko: </td>" . "<td>" . $result['lastname'] . "</td></tr>"; 
                echo "<tr><td>Adres: </td>" . "<td>" . $result['street'] . " " . $result['home_number'] . " " . $result['city'] . "</td></tr>"; 
            ?>
            </tbody>
        </table>

        <a href="edit.php"><button type="submit">Edytuj</button></a>

</div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
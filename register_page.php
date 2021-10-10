<?php

    session_start();
	
	if ((isset($_SESSION['logged_in'])) && ($_SESSION['logged_in'] == true))
	{
        if ($_SESSION['is_worker'] == true)
            header('Location: admin_panel.php');
        else 
            header('Location: user_panel.php');
		exit();
    }

    if (isset($_POST['bang'])) {
        
        $fields = ['login', 'firstname', 'lastname', 'street', 'home_number', 'city', 'pass', 'pass2'];
        foreach ($fields as $f) {
            if (!isset($_POST[$f]) || strlen($_POST[$f]) < 1) {
                $_SESSION['bad_register'] = 'Wypełnij wszystkie pola w formularzu!';
                $_SESSION['good_register'] = "";
                header('Location: register_page.php');
                exit();
            }
        }
        
        // Check the password
        $login = $_POST['login'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $street = $_POST['street'];
        $homeNumber = $_POST['home_number'];
        $city = $_POST['city'];
        $pass = $_POST['pass'];
        $pass2 = $_POST['pass2'];
        
        if ($pass != $pass2)
        {
            $_SESSION['bad_register'] = "Podane hasła nie są identyczne!";
            $_SESSION['good_register'] = "";
            header('Location: register_page.php');
            exit();
        }	

        $hashPass = password_hash($pass, PASSWORD_DEFAULT);		
        
        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);
        
        try 
        {
            $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
            if ($conn->connect_errno != 0)
            {
                throw new Exception(mysqli_connect_errno());
            }
            else
            {
                // check if such user already exists
                $result = $conn->query("SELECT id_user FROM user WHERE login='$login';");
                
                if (!$result) throw new Exception($conn->error);
                
                $loginCnt = $result->num_rows;
                if($loginCnt > 0)
                {
                    $_SESSION['bad_register'] = "Istnieje już użytkownik o takim loginie! Wybierz inny.";
                    $_SESSION['good_register'] = "";
                    header('Location: register_page.php');
                    exit();
                }
                
                    
                if ($conn->query("INSERT INTO user VALUES (NULL, '$firstname', '$lastname', '$city', '$street', '$homeNumber', '$login', 0, '$hashPass');"))
                {
                    $_SESSION['good_register'] = 'Rejestracja ukończona pomyślnie!';
                    $_SESSION['bad_register'] = "";
                    header('Location: register_page.php');
                    exit();
                }
                else
                {
                    throw new Exception($conn->error);
                }
                    
                
                $conn->close();
            }
            
        }
        catch(Exception $e)
        {
            echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
        }
            
    }


?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Zarejestruj się</title>
    <link rel="stylesheet" type="text/css" href="styles/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>

<body>

<div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Pitcernia</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse flex-row-reverse" id="navbarNavDropdown">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Strona główna<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#">Rejestracja</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login_page.php">Logowanie</a>
            </li>
            </ul>
        </div>
        </nav>

        <div class="container">
            <h1 class="text-center myHeader">Zarejestruj się</h1>

            <div class="d-flex justify-content-center">
                <form action="register_page.php" method="post">
        
                    Login: <br /> <input type="text" name="login" /> <br />
                    Imię: <br /> <input type="text" name="firstname" /> <br />
                    Nazwisko: <br /> <input type="text" name="lastname" /> <br />
                    Ulica: <br /> <input type="text" name="street" /> <br />
                    Nr domu: <br /> <input type="text" name="home_number" /> <br />
                    Miasto: <br /> <input type="text" name="city" /> <br />
                    Hasło: <br /> <input type="password" name="pass" /> <br /><br />
                    Potwierdź hasło: <br /> <input type="password" name="pass2" /> <br /><br />
                    <input type="submit" name="bang" value="Zarejestruj się" />

                </form>
            </div>

            <?php
            if (isset($_SESSION['bad_register']) && $_SESSION['bad_register'])	{
                echo "<span style='color:red;'>" . $_SESSION['bad_register'] . "</span>";
                unset($_SESSION['bad_register']);
            }
            if (isset($_SESSION['good_register']) && $_SESSION['good_register'])	{
                echo "<span style='color:green;'>" . $_SESSION['good_register'] . "</span>";
                unset($_SESSION['good_register']);
            }
            ?>
        </div>


    </div>
	
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
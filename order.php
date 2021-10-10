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
	
    // set up connection with DB
    require_once "connect.php";
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $conn->set_charset('utf8');
    
    // find the number of pizzas in DB
    $sql = "SELECT COUNT(*) FROM menu;";
    $result = $conn->query($sql);
    $pizzaCnt = $result->fetch_assoc();
    $pizzaCnt = $pizzaCnt['COUNT(*)'];
    //print_r($pizzaCnt);

    // validate data from user
    $correctOrder = false;
    $orderedPizzas = [];
    $totalPrice = 0;

    for ($i = 1; $i <= $pizzaCnt; $i++) {
        $howMany = $_POST[$i];
        if ($howMany < 0) {
            $_SESSION['bad_order'] = '<span style="color:red">Nieprawidłowa liczba produktów </span>';
            header('Location: user_panel.php');
            exit();
        }
        if ($howMany > 0) {
            $correctOrder = true;
            $orderedPizzas[$i] = $howMany;
            $sql = "SELECT price FROM menu WHERE id_menu=$i;";
            $result = $conn->query($sql);
            $price = $result->fetch_assoc();
            $price = $price['price'];
            $totalPrice += $howMany*$price;
        }
    }

    if (!$correctOrder) {
        $_SESSION['bad_order'] = '<span style="color:red">Nieprawidłowa liczba produktów </span>';
        header('Location: user_panel.php');
        exit();
    }

    // get current date
    $now = getdate();
    $currentMonth = $now['mon'];
    $currentYear = $now['year'];

    // generate genMonth
    $sql = "SELECT COUNT(*) FROM ordering WHERE YEAR(date)=$currentYear AND MONTH(date)=$currentMonth;";
    $result = $conn->query($sql);
    $soFar = $result->fetch_assoc();
    $soFar = $soFar['COUNT(*)'];
    // print_r($soFar);
    
    $soFar++;
    $a = strval($soFar);
    $b = strval($currentMonth);
    $c = strval($currentYear);
    $genMonth = $a . "/" . $b . "/" . $c;

    // add ordering to DB 
    $user_id = $_SESSION['id_user'];
    $sql = "INSERT INTO `ordering`(`id_ordering`, `user_id`, `date`, `month_gen`, `total_price`) VALUES (NULL, $user_id, NOW(), '$genMonth', $totalPrice);";
    $result = $conn->query($sql);

    if (!$result) {
        $_SESSION['bad_order'] = '<span style="color:red">Nie udało się wykonać zamówienia. Spróbuj ponownie. </span>';
        header('Location: user_panel.php');
        exit();
    }

    // get id of last added pizza
    $sql = "SELECT id_ordering FROM ordering WHERE month_gen = '$genMonth';";
    $result = $conn->query($sql);
    $ordering_id = $result->fetch_assoc();
    $ordering_id = $ordering_id['id_ordering'];
    print_r($ordering_id);

    // add all pizzas to helper table
    foreach ($orderedPizzas as $pizza => $cnt) {
        $sql = "INSERT INTO `ordering_menu`(`id_ordering_menu`, `ordering_id`, `menu_id`, `amount`) VALUES (NULL, $ordering_id, $pizza, $cnt);";
        $result = $conn->query($sql);

        if (!$result) {
            $_SESSION['bad_order'] = '<span style="color:red">Nie udało się wykonać zamówienia. Spróbuj ponownie. </span>';
            header('Location: user_panel.php');
            exit();
        }
    }

    unset($_SESSION['bad_order']);
    $conn->close();
    header('Location: history.php');
    exit();

?>
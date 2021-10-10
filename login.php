<?php

	session_start();
	
    if (isset($_SESSION['logged_in']) && $_SESSION['is_worker']) {
        header('Location: admin_panel.php');
    }
    if (isset($_SESSION['logged_in']) && !$_SESSION['is_worker']) {
        header('Location: user_panel.php');
    }

    
    require_once "connect.php";
	
	if ((!isset($_POST['login'])) || (!isset($_POST['pass'])))
	{
		header('Location: register_page.php');
		exit();
	}
	$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
	
	if ($conn->connect_errno != 0)
	{
		echo "Error: ".$conn->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$pass = $_POST['pass'];
		
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		$pass = htmlentities($pass, ENT_QUOTES, "UTF-8");
	
		if ($result = @$conn->query(
		sprintf("SELECT * FROM user WHERE login='%s'",
		mysqli_real_escape_string($conn, $login))))
		{
			$user_cnt = $result->num_rows;
			if($user_cnt > 0)
			{
				
				$row = $result->fetch_assoc();
				if (password_verify($pass, $row['password']))
				{	
					$_SESSION['logged_in'] = true;
					$_SESSION['id_user'] = $row['id_user'];
					$_SESSION['is_worker'] = $row['is_worker'];
					
					unset($_SESSION['error']);
                	$result->free_result();
                
					if ($_SESSION['is_worker'] == true)
						header('Location: admin_panel.php');
					else 
						header('Location: user_panel.php');
					
				}
				else 
				{
					$_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
					header('Location: login_page.php');
				}	
			} else {
				$_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
				header('Location: login_page.php');
			}
			
		}
		
		$conn->close();
	}
	
?>
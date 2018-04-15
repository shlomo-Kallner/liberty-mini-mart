<?php

	$to = 'test@test.com';  // please change this email id
	
	$errors = array();
	// print_r($_POST);

	// Check if name has been entered
	if (!isset($_POST['first_name'])) {
		$errors['first_name'] = 'Please enter your First name';
	}
	if (!isset($_POST['last_name'])) {
		$errors['last_name'] = 'Please enter your Last name';
	}
	if (!isset($_POST['phone'])) {
		$errors['phone'] = 'Please enter your Phone number.';
	}
	if (!isset($_POST['reserv_date'])) {
		$errors['reserv_date'] = 'Please enter your Reservation date.';
	}
	if (!isset($_POST['numb_guests'])) {
		$errors['numb_guests'] = 'Please enter your Guest numer.';
	}
	if (!isset($_POST['alt_reserv_date'])) {
		$errors['alt_reserv_date'] = 'Please enter your alternative reservation date.';
	}
	if (!isset($_POST['time'])) {
		$errors['time'] = 'Please enter your time of reservation.';
	}
	// Check if email has been entered and is valid
	if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = 'Please enter a valid email address';
	}
	

	$errorOutput = '';

	if(!empty($errors)){

		$errorOutput .= '<div class="alert alert-danger alert-dismissible" role="alert">';
 		$errorOutput .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';

		$errorOutput  .= '<ul>';

		foreach ($errors as $key => $value) {
			$errorOutput .= '<li>'.$value.'</li>';
		}

		$errorOutput .= '</ul>';
		$errorOutput .= '</div>';

		echo $errorOutput;
		die();
	}



	$name = $_POST['first_name'];
	$email = $_POST['email'];
	$from = $email;
	$subject = 'Contact Form : Meatking Responsive HTML5 Template';

	$message="First Name: ".$_POST['first_name']."<br>";
	$message.="Last Name: ".$_POST['last_name']."<br>";
	$message.="Phone: ".$_POST['phone']."<br>";
	$message.="Reservation Date: ".$_POST['reserv_date']."<br>";
	$message.="Total Guest: ".$_POST['numb_guests']."<br>";
	$message.="Alternative Reservation Date: ".$_POST['alt_reserv_date']."<br>";
	$message.="Time :".$_POST['time']."<br>";
	$message.=" Email: ".$_POST['email'];
	$body = "From: $name\n E-Mail: $email\n Message:\n $message";


	//send the email
	$result = '';
	if (mail ($to, $subject, $body)) {
		$result .= '<div class="alert alert-success alert-dismissible" role="alert">';
 		$result .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$result .= 'Request for reservation has been successfully accepted. You will be informed later.';
		$result .= '</div>';

		echo $result;
		die();
	}

	$result = '';
	$result .= '<div class="alert alert-danger alert-dismissible" role="alert">';
	$result .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	$result .= 'Something bad happend during sending this message. Please try again later';
	$result .= '</div>';

	echo $result;
	die();


?>

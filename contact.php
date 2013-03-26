<?php 

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$name = trim(strip_tags(($_POST["name"])));
		$email = trim(strip_tags(($_POST["email"])));
		$message = trim(strip_tags(($_POST["message"])));
		$error_message = array();

		//Start form validation
		if ($name == "" OR $email == "" OR $message == "") {
			$error_message[] = "Please specify a name, email and message.";
			
		}
		
		foreach ($_POST as $value) {
			if (stripos($value, 'Content-Type:') !== FALSE) {
				$error_message[] = "Problem with information entered.";	
			}
		}

		if ($_POST["address"] !== "") {
			$error_message[] = "Form submission error.";
		}

		//Load php mailer
		require_once('inc/class.phpmailer.php');
		$mail = new PHPMailer(); // defaults to using php "mail()"

		if (!$mail->ValidateAddress($email)) {
			$error_message[] = "Invalid email address.";
			
		}
		//End form validation

		if (empty($error_message)) {

			$email_body = "";
			$email_body = $email_body . "Name: " . $name . "<br>";
			$email_body = $email_body . "Email: " . $email . "<br>";
			$email_body = $email_body . "Message: " . $message;

			//Send Email
			$mail->SetFrom($email, $name);
			$address = "tom@trafficable.co.uk";
			$mail->AddAddress($address, "Tom Maddocks");
			$mail->Subject = "Website Enquiry" . $name;		
			$mail->MsgHTML($email_body);


			if($mail->Send()) {
				header("Location: contact.php?status=Thanks");
		 		exit;
			} else {  
			  	$error_message[] = "Mailer Error: " . $mail->ErrorInfo;
			}
		}

 	}
?>

<?php 
include('inc/header.php'); ?>

	<div class="section page">			

			<div class="wrapper">

				<h1>Get in touch!</h1>

				<?php if (isset($_GET["status"]) AND $_GET["status"] == "Thanks") { ?>

					<p>Thanks for the email! I&rsquo;ll be in touch shortly!</p>
				
				<?php } else { ?>


					<?php if (!isset($error_message)) { 
						echo '<p>I&rsquo;d love to hear from you! Complete the form to send me an email.</p>';
					} else {
						foreach ($error_message as $error) { echo '<p class="message">' . $error . '</p>'; }
					} ?>

					<form method="post" action="contact.php">

						<table>
							<tr>
								<th>

									<label for="name">Name:</label>

								</th>

								<td>

									<input type="text" name="name" id="name" value="<?php if(isset($name)) { echo htmlspecialchars($name); } ?>">

								</td>
							</tr>

							<tr>
								<th>

									<label for="email">Email:</label>

								</th>

								<td>

									<input type="text" name="email" id="email" value="<?php if(isset($email)) { echo htmlspecialchars($email); } ?>">

								</td>
							</tr>

							<tr>
								<th>

									<label for="message">Message:</label>

								</th>

								<td>

									<textarea name="message" id="message"><?php if(isset($message)) { echo htmlspecialchars($message); } ?></textarea>

								</td>
							</tr>

							<tr style="display: none;">
								<th>

									<label for="address">Address</label>

								</th>

								<td>

									<input type="text" name="address" id="address">
									<p>Please leave this field blank.</p>
								</td>
							</tr>

							<tr>
								<th>

									

								</th>

								<td>

									<input type="submit" value="Send">

								</td>
							</tr>

						</table>

					</form>

				<?php } ?>

			</div>

	</div>

<?php include('inc/footer.php'); ?>
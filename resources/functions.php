<?php 


function log_user_visit($db) {
	#$ip = getVisIPAddr();
	#$ip = '180.94.77.212'; # Test AFG
	$ip = '52.25.109.230'; # Test USA
	$ipdat = @json_decode(file_get_contents(
		'http://www.geoplugin.net/json.gp?ip=' . $ip));

	$stmt = $db->prepare("INSERT INTO VISITS (COUNTRY, CITY, CONTINENT, LATITUDE, LONGITUDE, CURRENCY_SYMBOL, CURRENCY_CODE, TIMEZONE) VALUES (:country, :city, :continent, :latitude, :longitude, :currencySymbol, :currencyCode, :timezone)");
	$stmt->bindValue(':country', $ipdat->geoplugin_countryName);
	$stmt->bindValue(':city', $ipdat->geoplugin_city);
	$stmt->bindValue(':continent', $ipdat->geoplugin_continentName);
	$stmt->bindValue(':latitude', $ipdat->geoplugin_latitude);
	$stmt->bindValue(':longitude', $ipdat->geoplugin_longitude);
	$stmt->bindValue(':currencySymbol', $ipdat->geoplugin_currencySymbol);
	$stmt->bindValue(':currencyCode', $ipdat->geoplugin_currencyCode);
	$stmt->bindValue(':timezone', $ipdat->geoplugin_timezone);
	$stmt->execute();
}

function get_visitor_data($db) {
	$query = "SELECT COUNT(*) AS USER_COUNT, COUNTRY FROM VISITS GROUP BY COUNTRY";
	$results = $db->query($query);

	$visits = array();
	while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
		$visits[] = $row;
        }
	return $visits;
}

function get_phrases($db) {
	$query = "SELECT KEY, VALUE FROM PHRASES WHERE LANGUAGE_ISO_CODE = '{$_SESSION['language']}'";
	$results = $db->query($query);

	$phrases = array();
	while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
		$phrases[$row['KEY']] = $row['VALUE'];
	}

	return $phrases;
}

function get_languages($db) {
	$query = "SELECT ISO_CODE, NAME FROM LANGUAGES";
	$results = $db->query($query);

	$languages = array();
	while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
		$languages[] = $row;
	}

	return $languages;
}


function getVisIpAddr() { 
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
		return $_SERVER['HTTP_CLIENT_IP']; 
	} 
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
		return $_SERVER['HTTP_X_FORWARDED_FOR']; 
	} 
	else { 
		return $_SERVER['REMOTE_ADDR']; 
	} 
} 

function send_mail($smtp_server, $sender_email, $password, $sender_name, $recipient_email, $recipient_name, $email_title, $email_body) {
        //Load Composer's autoloader
        require 'vendor/autoload.php';

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
                //Server settings
                /*$mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER; //Enable verbose debug output*/
                $mail->isSMTP(); //Send using SMTP
                $mail->Host = $smtp_server; //Set the SMTP server to send through
                $mail->SMTPAuth = true; //Enable SMTP authentication
                $mail->Username = $sender_email; //SMTP username
                $mail->Password = $password; //SMTP password
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom($sender_email, $sender_name);
                $mail->addAddress($recipient_email, $recipient_name); //Add a recipient
                /*$mail->addAddress('ellen@example.com'); //Name is optional*/
                /*$mail->addReplyTo('info@example.com', 'Information');*/
                /*$mail->addCC('cc@example.com');*/
                /*$mail->addBCC('bcc@example.com');*/

                //Attachments
                /*$mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments*/
                /*$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name*/

                //Content
                $mail->isHTML(true); //Set email format to HTML
                $mail->Subject = $email_title;
                $mail->Body = $email_body;
                /*$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';*/

                $mail->send();
                /*echo 'Message has been sent';*/
		return 'success';
        } catch (PHPMailer\PHPMailer\Exception $e) {
                /*echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";*/
		return 'failed';
        }
}


<?php


/**
 * SMTP Mail function using Gmail credentials
 * Using PHPMailer Class Library
 */

function smtpmail($to, $from='', $replyto='', $subject, $data = '', $username='', $password='', $attachfilepath=''){

    
    // Loading PHPMailer classs

    require __DIR__.'/../PHPMailer/PHPMailerAutoload.php';
    
    $from = 'ihis.yamuna@gmail.com';
    $replyto = 'ihis.yamuna@gmail.com';

    $mail = new PHPMailer;

    $mail->isSMTP();                                        // Set mailer to use SMTP

    //$mail->SMTPDebug = 3;
    
    $mail->Host = 'smtp.gmail.com';                         // Specify main and backup SMTP servers

    $mail->SMTPAuth = true;                                 // Enable SMTP authentication

    $mail->Username = "ihis.yamuna@gmail.com";                              // SMTP username - Insert Email Address
    $mail->Password = "ihis1234";                           // SMTP password - Insert Email Account Password


    //$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    //$mail->Port = 465; 

    $mail->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                      // TCP port to connect to  587 / 465


    $mail->setFrom($from, 'Digibook');
    $mail->addReplyTo($replyto, 'Digibook');

    //Adding Subject
    $mail->Subject = $subject;


    // Adding Recipient
    if(is_array($to)){

        // sending mail to all recipient
        for($m=0; $m < count($to); $m++){
            $mail->addAddress($to[$m]);
        }

    } else {

        $mail->addAddress($to);
    }

    $mail->isHTML(true);  // Set email format to HTML

    

    $mail->Body = $data;
    
    //add Attachment file 
    if( !empty($attchfilepath) ){
        $mail->AddAttachment($attachfilepath
        );
    }

    if(!$mail->send()) {
        return $mail->ErrorInfo;
    } else {
        return true;
    }

}

function sendsms($message, $mobileNos){

    $curl = curl_init();

    $encodeMessage = json_encode(
        array(
            "smsContent" => "$message",
            "groupId" => "0",
            "routeId" => "1",
            "mobileNumbers" => "$mobileNos",
            "senderId" => "DEMOOS",
            "signature" => "Yamuna Meditech",
            "smsContentType" => "english"
        )
    );


    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://167.114.117.218/rest/services/sendSMS/sendGroupSms?AUTH_KEY=595aae1c19e7a4ac17a1a7a94f57b250",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "$encodeMessage",
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        print_r(json_decode($response));
    }
}




?>
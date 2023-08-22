<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    date_default_timezone_set('Europe/Paris');

    require_once __DIR__ . '/vendor/PHPMailer/PHPMailer.php';
    require_once __DIR__ . '/vendor/PHPMailer/SMTP.php';
    require_once __DIR__ . '/vendor/recaptcha/autoload.php';

    /**
     * Ajax_Form - Send email using ajax with validations security
     * 
     * @see      https://github.com/raspgot/AjaxForm-PHPMailer-reCAPTCHA
     * @package  PHPMailer | reCAPTCHA v3
     * @author   Gauthier Witkowski <contact@raspgot.fr>
     * @link     https://raspgot.fr
     * @version  1.0.2
     */

    class Ajax_Form {
        
        # Constants to redefined
        const HOST        = 'p3plcpnl0518.prod.phx3.secureserver.net';
        const USERNAME    = 'hola@kilimanjjjaro.com';
        const PASSWORD    = '(bh0=4Hxp,Mx';
        const SMTP_SECURE = false;
        const SMTP_AUTH   = false;
        const PORT        = 25;
        const SECRET_KEY  = '6LfAUOEUAAAAAImwBsO-Ftz1gtMBAxHLhAmIt7Ug';
        const SUBJECT     = 'New message !';
        public $handler   = [
            'success'         => 'Your message has been sent 🙂',
            'recaptcha-error' => 'Error in recaptcha response',
            'error'           => 'Sorry, an error occurred while sending your message 😕',
            'enter_name'      => 'Please enter your name.',
            'enter_email'     => 'Please enter a valid email.',
            'enter_message'   => 'Please enter your message.',
            'ajax_only'       => 'Asynchronous anonymous 🎭',
            'body'            => '
                <h1>{{subject}}</h1>
                <p><strong>Name :</strong> {{name}}</p>
                <p><strong>E-Mail :</strong> {{email}}</p>
                <p><strong>Message :</strong> {{message}}</p>
            ',
        ];

        /**
         * Ajax_Form __constructor
         */
        public function __construct() {

            # Check if request is Ajax request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' !== $_SERVER['HTTP_X_REQUESTED_WITH']) {
                $this->statusHandler('ajax_only', 'error');
            }

            # Get secure post data
            $name    = filter_var($this->secure($_POST['name']), FILTER_SANITIZE_STRING);
            $email   = filter_var($this->secure($_POST['email']), FILTER_SANITIZE_EMAIL);
            $message = filter_var($this->secure($_POST['message']), FILTER_SANITIZE_STRING);

            # Check if fields has been entered and valid
            if (!$name) $this->statusHandler('enter_name', 'error');
            if (!$email) $this->statusHandler('enter_email', 'error');
            if (!$message) $this->statusHandler('enter_message', 'error');

            # Prepare body
            $body = $this->getString('body');
            $body = $this->template( $body, [
                'subject' => self::SUBJECT,
                'name'    => $name,
                'email'   => $email,
                'message' => $message,
            ] );

            # Verifying the user's response
            $recaptcha = new \ReCaptcha\ReCaptcha(self::SECRET_KEY);
            $resp = $recaptcha
                ->setExpectedHostname($_SERVER['SERVER_NAME'])
                ->verify($_POST['token'], $_SERVER['REMOTE_ADDR']);

            if ($resp->isSuccess()) {

                $mail = new PHPMailer(true);

                try {
    //Server settings
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'p3plcpnl0518.prod.phx3.secureserver.net';
    $mail->SMTPAuth = false;                               // Enable SMTP authentication
    $mail->Username = 'hola@kilimanjjjaro.com';                 // SMTP username
    $mail->Password = '369671891010';                           // SMTP password
    $mail->SMTPSecure = 'false';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25;                                    // TCP port to connect to
                
    //Recipients
    $mail->setFrom('noreply@kilimanjjjaro.com', 'Kilimanjjjaro');
    $mail->addAddress('hola@kilimanjjjaro.com', 'Kilimanjjjaro');
                
    //Content
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Nuevo mensaje desde Kilimanjjjaro';
    $mail->Body    = '<br><img src="http://kilimanjjjaro.com/img/mail.png" alt="Kilimanjjjaro"><br><br><br>Te enviaron un mensaje desde tu formulario de contacto.<br><br><b>Nombre:</b> '.$_POST['name']. '<br><b>Correo electrónico:</b> '.$_POST['email']. '<br><b>Mensaje:</b> '.$_POST['message']. '<br><br>¡Que tengas un buen día!';
                
                    $mail->send();
                    $this->statusHandler('success', 'success');

                } catch (Exception $e) {
                    $this->statusHandler('error', 'error');
                }
            } else {
                $this->statusHandler('recaptcha-error', 'error');
            }
        }

        /**
         * Template string
         *
         * @param string $string
         * @param array $vars
         * @return string
         */
        public function template($string, $vars)
        {
            foreach ($vars as $name => $val) {
                $string = str_replace("{{{$name}}}", $val, $string);
            }
            return $string;
        }

        /**
         * Get string from $string variable
         *
         * @param string $string
         * @return string
         */
        public function getString($string)
        {
            return isset($this->handler[$string]) ? $this->handler[$string] : $string;
        }

        /**
         * Secure inputs fields
         *
         * @param string $post
         * @return string
         */
        public function secure($post)
        {
            $post = htmlspecialchars($post);
            $post = stripslashes($post);
            $post = trim($post);
            return $post;
        }

        /**
         * Error or success message
         *
         * @param string $message
         * @param string $status
         * @return json
         */
        public function statusHandler($message, $status)
        {
            die(json_encode([
                'type'     => $status,
                'response' => $this->getString($message)
            ]));
        }

    }

    # Instanciation 
    new Ajax_Form();
?>
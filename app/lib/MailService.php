<?php

    @require_once 'PHPMailer.php';
    @require_once 'SMTP.php';

    class MailService {

        // Atributos
        private $mailer;
        public $connection = array(
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'smtp' => true,
            'secure' => 'tls',
            'auth' => true,
            'login' => 'desenv.software.agr@gmail.com',
            'password' => '?'
        );
        public $mail = array(
            'from' => 'desenv.software.agr@gmail.com',
            'sender' => 'Equipe de Desenvolvimento de Software da AGR',
            'to' => array(),
            'subject' => '',
            'body' => '',
            'alt_body' => '',
            'html' => true,
            'charset' => 'UTF-8',
            'attachment' => array()
        );

        // Construtor
        public function __construct() {
            // Configura o expedidor de mensagens.
            $this->mailer = new PHPMailer();
            $this->mailer->Host = $this->connection['host'];
            $this->mailer->Port = $this->connection['port'];
            if ($this->connection['smtp']) {
                $this->mailer->isSMTP();
            }
            // Protocolo de segurança utilizado (SSL ou TLS).
            $this->mailer->SMTPSecure = $this->connection['secure'];
            $this->mailer->SMTPAuth = $this->connection['auth'];
            $this->mailer->Username = $this->connection['login'];
            $this->mailer->Password = $this->connection['password'];
        }

        // Destrutor
        public function __destruct() {
            unset($this->mailer);
        }

        // Getters
        public function __get($field) {
            return $this->$field;
        }

        // Setters
        public function __set($field, $value) {
            $this->$field = $value;
        }

        public function send_mail() {
            // Remetente.
            $this->mailer->setFrom($this->mail['from'], $this->mail['sender']);
            // Destinatários.
            foreach ($this->mail['to'] as $email => $name) {
                $this->mailer->addAddress($email, $name);
            }
            $this->mailer->Subject = $this->mail['subject'];
            $this->mailer->isHTML($this->mail['html']);
            $this->mailer->CharSet = $this->mail['charset'];
            $this->mailer->Body = $this->mail['body'];
            $this->mailer->AltBody = $this->mail['alt_body'];
            $this->mailer->send();
            /*
            if (!$this->mailer->send()) {
                echo 'Erro ao enviar o e-mail:' . $this->mailer->ErrorInfo;
            } else {
                echo 'E-mail enviado com sucesso!';
            }
            */
        }

        public function  add_receiver() {
            
        }
    }
?>
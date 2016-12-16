<?php
namespace Vokuro\Mail;

use Phalcon\Mvc\User\Component;
use Swift_Message as Message;
use Swift_SmtpTransport as Smtp;
use Phalcon\Mvc\View;

/**
 * Vokuro\Mail\Mail
 * Sends e-mails based on pre-defined templates
 */
class Mail extends Component
{

    protected $transport;

    protected $amazonSes;

    protected $directSmtp = true;

    protected $from = null;
    protected $from_name = null;

    /**
     * @param null $from
     */
    public function setFrom($from,$from_name=null)
    {
        if($from_name!='')
        {
          $this->from_name = $from_name;
        }
        
        $this->from = $from;
        
        
       
    }

    /**
     * Send a raw e-mail via AmazonSES
     *
     * @param string $raw
     */
    private function amazonSESSend($raw)
    {
        if ($this->amazonSes == null) {
            $this->amazonSes = new \AmazonSES(
                $this->config->amazon->AWSAccessKeyId,
                $this->config->amazon->AWSSecretKey
            );
            $this->amazonSes->disable_ssl_verification();
        }

        $response = $this->amazonSes->send_raw_email(array(
            'Data' => base64_encode($raw)
        ), array(
            'curlopts' => array(
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            )
        ));

        if (!$response->isOK()) {
            throw new Exception('Error sending email from AWS SES: ' . $response->body->asXML());
        }

        return true;
    }

    /**
     * Applies a template to be used in the e-mail
     *
     * @param string $name
     * @param array $params
     */
    public function getTemplate($name, $params)
    {
        $tDomain = explode('.', $_SERVER['HTTP_HOST']);
        $End = array_pop($tDomain);
        $Domain = array_pop($tDomain);
        $TLDomain = "{$Domain}.{$End}";
        $parameters = array_merge(array(
            'publicUrl' => $TLDomain,
        ), $params);

        return $this->view->getRender('emailTemplates', $name, $parameters, function ($view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });

        return $view->getContent();
    }

    /**
     * Sends e-mails via AmazonSES based on predefined templates
     *
     * @param array $to
     * @param string $subject
     * @param string $name
     * @param array $params
     */
    public function send($to, $subject, $name, $params, $template = '')
    {
        // Settings
        $mailSettings = $this->config->mail;

        if ($template == '') $template = $this->getTemplate($name, $params);

        if(!$this->from)
            $this->from = $mailSettings->fromEmail;

        if(!$this->from_name)
        {
            $this->from_name= $mailSettings->fromName;
        }

        // Create the message
         if(!$this->from_name)
        {
        $message = Message::newInstance()
            ->setSubject($subject)
            ->setTo($to)
            ->setFrom($this->from)
            ->setBody($template, 'text/html');
        }
        else
        {
            $message = Message::newInstance()
            ->setSubject($subject)
            ->setTo($to)
            ->setFrom(array(
                  $this->from => $this->from_name
              ))
            ->setBody($template, 'text/html'); 
        }




        // To send HTML mail, the Content-type header must be set
        //$headers  = 'MIME-Version: 1.0' . "\r\n";
        //$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        //$mailSettings->SMTPDebug  = 2;

        // Additional headers
        //$explode_emails = $to;//implode(",", $to);
        //$headers .= 'To: '.$explode_emails . "\r\n";
        //$headers .= 'From: '.$mailSettings->fromEmail;// . "\r\n" .
          //'Return-Path: ' . $mailSettings->fromEmail . "\r\n" . 'X-Mailer: PHP/' . phpversion(). "\r\n";

        //echo '<p>headers:'.$headers.'</p>';
        //echo '<p>$mailSettings->fromEmail:'.$mailSettings->fromEmail.'</p>';
        //exit();

        // Mail it
        //$success = mail($explode_emails, $subject, $template, $headers);//, "-f ".$mailSettings->fromEmail." -r ".$mailSettings->fromEmail);
        //echo '<p>$success:'.($success?'true':'false').'</p>';
        //return $success;

        //if ($this->directSmtp) {

            if (!$this->transport) {
                $this->transport = Smtp::newInstance(
                    $mailSettings->smtp->server,
                    $mailSettings->smtp->port,
                    $mailSettings->smtp->security
                )
                ->setUsername($mailSettings->smtp->username)
                ->setPassword($mailSettings->smtp->password);
            }

            // Create the Mailer using your created Transport
            $mailer = \Swift_Mailer::newInstance($this->transport);

            return $mailer->send($message);
        //} else {
        //    return $this->amazonSESSend($message->toString());
        //}
    }
}

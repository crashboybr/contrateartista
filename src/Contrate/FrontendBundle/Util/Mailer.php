<?php

namespace Contrate\FrontendBundle\Util;


class Mailer 
{
    protected $mailer;
    protected $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendEmail($entity, $type)
    {
        //die('a');

        // Load the template in
        if ($type == 'newartist') {
            //$to_email = 'bernardoniteroi@gmail.com';
            //$to_email = 'michelkneit@gmail.com';
            $to_email = 'marketing@contrateartistas.com';
        } else {
            $user = $entity->getArtist()->getUser();
            $to_email = $user->getEmail();
        }

        $templateFile = "ContrateFrontendBundle:Email:email-" . $type . ".html.twig";
        //$templateFile = "iListFrontendBundle:Email:email-published.html.twig";
        $templateContent = $this->twig->loadTemplate($templateFile);

        // Render the whole template including any layouts etc

        $body = $templateContent->renderBlock("body", array("entity" => $entity));

        $subject = ($templateContent->hasBlock("subject")
            ? $templateContent->renderBlock("subject", array())
            : "Contrate Artista");


        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom("contato@contrateartistas.com")
            ->setTo($to_email)
            //->setCco('bernardoniteroi@gmail.com')
            //->setBcc('bernardo.d.alves@gmail.com')
            ->setBcc('marketing@contrateartistas.com')
            ->setBody($body, 'text/html');
            //echo "<pre>";var_dump($message);

        $this->mailer->send($message);
        //die('foi');
    }

    
}

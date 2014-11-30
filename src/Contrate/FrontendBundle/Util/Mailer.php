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
        $user = $entity->getArtist()->getUser();

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
            ->setFrom("contato@contrateartista.com.br")
            ->setTo($user->getEmail())
            //->setCco('bernardoniteroi@gmail.com')
            ->setBcc('bernardo.d.alves@gmail.com')
            ->setBody($body, 'text/html');
            //echo "<pre>";var_dump($message);

        $this->mailer->send($message);
        //die('foi');
    }

    
}

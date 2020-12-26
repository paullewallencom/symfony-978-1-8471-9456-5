<?php

/**
 * signup actions.
 *
 * @package    milkshake
 * @subpackage signup
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class alSignupActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new AlSignupNewsletterSignupForm();
    return sfView::SUCCESS;
  }

  public function executeSubmit(sfWebRequest $request)
  {
      $this->form = new AlSignupNewsletterSignupForm();


     if ($request->isMethod('post') && $this->form->bindAndSave($request->getParameter($this->form->getName())))
     {
        //Include file as a fix
        require_once('lib/vendors/swift-mailer/lib/swift_init.php');

        

        //set Flash
        $this->getUser()->setFlash('Form', 'completed');
        
        try{
          //Sendmail
          $transport = Swift_SendmailTransport::newInstance();
          $mailBody = $this->getPartial('activationEmail', array('name' => $this->form->getValue('first_name')));
          $mailer = Swift_Mailer::newInstance($transport);
          $message = Swift_Message::newInstance();
          $message->setSubject(sfConfig::get('mod_alsignup_mailer_subject'));
          $message->setFrom(array(sfConfig::get('mod_alsignup_mailer_from_email') => sfConfig::get('mod_alsignup_mailer_from_name')));
          $message->setTo(array($this->form->getValue('email')=> $this->form->getValue('first_name')));
          $message->setBody($mailBody, 'text/html');

          if(sfConfig::get('mod_alsignup_mailer_deliver'))
          {
            $result = $mailer->send($message);
          }
        }
        catch(Exception $e)
        {
           var_dump($e);
           $this->forward404('cannot send email');
        }
          
        $this->redirect('@signup');
     }
     //Use the index template as it contains the form
    $this->setTemplate('index');
  }
}

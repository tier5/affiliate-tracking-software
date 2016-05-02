<?php
namespace Vokuro\Controllers;

//TURN ON PRETTY ERRORS!!!
error_reporting(E_ALL);
ini_set("display_errors","on");

/**
 * Display the "About" page.
 */
class AboutController extends ControllerBase
{

    /**
     * Default action. Set the public layout (layouts/public.volt)
     */
    public function indexAction()
    {
        $this->view->setTemplateBefore('public');
    }
}

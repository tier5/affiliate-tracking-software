<?php
namespace Vokuro\Controllers;

/**
 * Display the privacy page.
 */
class PrivacyController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Review Velocity | Privacy Policy');
        parent::initialize();
    }

    /**
     * Default action. Set the public layout (layouts/public.volt)
     */
    public function indexAction()
    {
        $this->view->setTemplateBefore('public');
    }
}

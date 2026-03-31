<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This class is a bridge between CodeIgniter3 and Twig
 */
class Twig
{
    /**
     * @var Twig\Environment
     */
    private $twig;
    /**
     * @var Twig\Loader\FilesystemLoader
     */
    private $loader;

    /**
     * @var CI_Controller Code Igniter framework
     */
    private $CI;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Access to CI framework so as to use other libraries
        $this->CI = &get_instance();

        // Define the path to the views
        $this->loader = new \Twig\Loader\FilesystemLoader(VIEWPATH);

        // Twig configuration (cache, debug, etc.)
        $this->twig = new \Twig\Environment($this->loader, [
            'cache' => APPPATH . 'cache/twig',
            'auto_reload' => TRUE, // Useful in development
            'debug' => TRUE
        ]);

        // Add global CI functions to use in Twig
        $this->twig->addFunction(new \Twig\TwigFunction('base_url', 'base_url'));
        $this->twig->addFunction(new \Twig\TwigFunction('lang', 'lang'));
    }

    /**
     * Render a Twig template
     * @param string $view name of the template to render
     * @param array $data data to pass to the template
     * @return string the rendered template
     */
    public function render(string $view, array $data = []): string
    {
        // Add the .twig extension if it's not present
        $view = (strpos($view, '.twig') === false) ? $view . '.twig' : $view;
        $data['app'] = [
            'user' => $this->CI->session->userdata('user'),
            'is_admin' => $this->CI->session->userdata('is_admin'),
            'is_hr' => $this->CI->session->userdata('is_hr'),
            'is_manager' => $this->CI->session->userdata('is_manager'),
            'request' => $this->CI->input->get(),
            'flashes' => $this->CI->session->flashdata(),
            'environment' => ENVIRONMENT
        ];
        return $this->twig->render($view, $data);
    }

    /**
     * Display a Twig template
     * @param string $view name of the template to display
     * @param array $data data to pass to the template
     * @return void
     */
    public function display(string $view, array $data = []): void
    {
        echo $this->render($view, $data);
    }
}

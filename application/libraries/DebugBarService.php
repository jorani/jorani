<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use DebugBar\StandardDebugBar;
use DebugBar\JavascriptRenderer;

/**
 * DebugBarService
 * 
 * This class is a service for DebugBar
 */
class DebugBarService
{
    /** @var StandardDebugBar|null */
    protected $debugBar;

    /** @var JavascriptRenderer|null */
    protected $renderer;

    /**
     * Bootstrap the DebugBar
     */
    public function bootstrap(): void
    {
        if (!$this->isEnabled()) {
            return;
        }

        if ($this->debugBar !== null) {
            return;
        }

        $this->debugBar = new StandardDebugBar();
        $this->renderer = $this->debugBar->getJavascriptRenderer();

        // Example : custom measure
        $this->debugBar['time']->startMeasure('ci_request', 'CodeIgniter request');
    }

    /**
     * Finalize the DebugBar
     */
    public function finish(): void
    {
        if (!$this->isEnabled() || $this->debugBar === null) {
            return;
        }

        $this->debugBar['time']->stopMeasure('ci_request');

        // Collect SQL queries
        $sqlCollector = new \DebugBar\DataCollector\MessagesCollector('sql');
        $this->debugBar->addCollector($sqlCollector);

        $CI =& get_instance();
        if ($CI) {
            $totalQueries = 0;
            $totalTime = 0;
            $dbs = [];

            foreach (get_object_vars($CI) as $CI_object) {
                if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB')) {
                    $dbs[] = $CI_object;
                }
            }

            foreach ($dbs as $db) {
                if (!empty($db->queries) && is_array($db->queries)) {
                    foreach ($db->queries as $key => $val) {
                        $time = isset($db->query_times[$key]) ? $db->query_times[$key] : 0;
                        $timeMs = number_format($time * 1000, 2);
                        $sqlCollector->addMessage(sprintf("[%s ms] %s", $timeMs, $val), 'info');
                        $totalQueries++;
                        $totalTime += $time;
                    }
                }
            }

            if ($totalQueries > 0) {
                $sqlCollector->addMessage(sprintf("Total Queries: %d - Total Time: %s ms", $totalQueries, number_format($totalTime * 1000, 2)), 'info');
            }
        }
    }

    /**
     * Check if DebugBar is enabled
     */
    public function isEnabled(): bool
    {
        return (bool) config_item('debugbar_enabled');
    }

    /**
     * Check if DebugBar should be rendered in footer
     */
    public function shouldRenderInFooter(): bool
    {
        return $this->isEnabled() && (bool) config_item('debugbar_render_in_footer');
    }

    /**
     * Render the DebugBar head
     */
    public function renderHead(): string
    {
        if (!$this->isEnabled() || $this->renderer === null) {
            return '';
        }

        return $this->renderer->renderHead();
    }

    /**
     * Render the DebugBar footer
     */
    public function renderFooter(): string
    {
        if (!$this->shouldRenderInFooter() || $this->renderer === null) {
            return '';
        }

        return $this->renderer->render();
    }

    /**
     * Get the DebugBar instance
     */
    public function getDebugBar(): ?StandardDebugBar
    {
        return $this->debugBar;
    }
}

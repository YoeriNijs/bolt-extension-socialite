<?php

namespace Bolt\Extension\Bolt\Socialite;

use Bolt\Extensions\Snippets\Location as SnippetLocation;

/**
 * HTML element footprint based on file time
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class Extension extends \Bolt\BaseExtension
{
    public function getName()
    {
        return "socialite";
    }

    public function initialize() {

        // Define the path to us
        $this->config['path'] = substr(__DIR__, strlen($this->app['paths']['rootpath']));
        $this->config['url'] = $this->app['paths']['canonicalurl'];

        // If we're set to actviate by scroll, add a class to <body> that gets
        // caught in socialite.load.js
        if (empty($this->config['activation']) || $this->config['activation'] = 'scroll') {
            $html = '<script type="text/javascript">document.body.className += "socialite-scroll";</script>';
        }

        if (empty($this->config['template'])) {
            $this->config['template'] = 'socialite.twig';
        }

        // Insert out JS late so that we are more likely to work with a late
        // jQuery insertion
        $html .= '
            <script type="text/javascript" defer src="' . $this->config['path'] . '/js/bolt.socialite.min.js"></script>
            ';
        $this->insertSnippet(SnippetLocation::END_OF_HTML, $html);

        // Add ourselves to the Twig filesystem path
        $this->app['twig.loader.filesystem']->addPath(__DIR__ . '/assets/');

        // Catch the TWIG function
        $this->addTwigFunction('socialite', 'twigSocialite');
    }


    public function twigSocialite($buttons, $sep = '')
    {
        $this->widget = new Widget();

        return $this->widget->createWidget($this->app, $this->config, $buttons);
    }
}

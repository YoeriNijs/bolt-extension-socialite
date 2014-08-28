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
        return "Socialite";
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
        $this->addSnippet(SnippetLocation::END_OF_HTML, $html);

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

    protected function getDefaultConfig()
    {
        return array(
            'facebook_app_id' => '',
            'facebook_like_action' => 'like',
            'facebook_like_colorscheme' => 'light',
            'facebook_like_kid_directed_site' => 'false',
            'facebook_like_show_faces' => 'false',
            'facebook_like_layout' => 'box_count',
            'facebook_like_width' => '60',
            'facebook_follow_action' => 'like',
            'facebook_follow_colorscheme' => 'light',
            'facebook_follow_kid_directed_site' => 'false',
            'facebook_follow_show_faces' => 'false',
            'facebook_follow_layout' => 'box_count',
            'facebook_follow_width' => '60',
            'facebook_facepile_max_rows' => '1',
            'facebook_facepile_colorscheme' => 'light',
            'facebook_facepile_size' => 'medium',
            'facebook_facepile_count' => 'true',
            'google_plus_share_annotation' => 'vertical-bubble',
            'google_plus_share_size' => 'medium',
            'google_plus_share_relationship' => 'publisher',
            'google_plus_follow_annotation' => 'vertical-bubble',
            'google_plus_follow_size' => 'medium',
            'google_plus_follow_relationship' => 'publisher',
            'google_plus_badge_layout' => 'portrait',
            'google_plus_badge_width' => '300',
            'google_plus_badge_theme' => 'light',
            'google_plus_badge_photo' => 'enabled',
            'google_plus_badge_tagline' => 'enabled',
            'google_plus_badge_relationship' => 'publisher',
            'twitter_follow_align' => 'left',
            'twitter_follow_count' => 'horizontal',
            'twitter_follow_size' => 'medium',
            'twitter_share_align' => 'left',
            'twitter_share_count' => 'vertical',
            'twitter_share_size' => 'medium',
            'twitter_mention_align' => 'left',
            'twitter_mention_size' => 'medium',
            'twitter_hashtag_align' => 'left',
            'twitter_hashtag_size' => 'medium',
            'pinterest_pinit_config' => 'above',
            'pinterest_pinit_color' => 'red',
            'pinterest_pinit_size' => 'small',
            'pinterest_pinit_language' => 'en',
            'pinterest_pinit_hover' => 'on',
            'bufferapp_count' => 'vertical',
            'bufferapp_twitter_user' => 'BoltCM',
            'github_user' => 'bolt',
            'github_repo' => 'bolt',
            'github_count' => 'true',
            'github_size' => 'large'
        );
    }
}

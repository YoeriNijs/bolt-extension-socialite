<?php

namespace Bolt\Extension\Bolt\Socialite;

use Bolt\Asset\File\JavaScript;
use Bolt\Asset\Snippet\Snippet;
use Bolt\Asset\Target;
use Bolt\Extension\SimpleExtension;
use Silex\Application;

/**
 * Socialite extension for Bolt.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class SocialiteExtension extends SimpleExtension
{
    /** @var bool */
    private $injected;

    /**
     * Handle our Twig
     *
     * @param string|array $buttons
     *
     * @return \Twig_Markup
     */
    public function twigSocialite($buttons)
    {
        $this->queueAssets();
        $app = $this->getContainer();

        return (new Widget())->createWidget($this->getConfig(), $app['twig'], $buttons, $app['path_resolver']->resolve('%files%'));
    }

    protected function queueAssets()
    {
        if ($this->injected) {
            return;
        }
        $this->injected = true;

        $app = $this->getContainer();
        $config = $this->getConfig();

        // If we're set to activate by scroll, add a class to <body> that gets
        // caught in socialite.load.js
        if ($config['activation'] === 'scroll') {
            $html = '<script>document.body.className += "socialite-scroll";</script>';
            $snippet = (new Snippet())->setCallback($html)->setLocation(Target::END_OF_BODY);
            $app['asset.queue.snippet']->add($snippet);
        }

        $webPath = $this->getWebDirectory()->getPath() .'/bolt.socialite.min.js';
        $js = (new JavaScript($webPath))
            ->setLate(true)
            ->setAttributes(['defer', 'async'])
            ->setLocation(Target::END_OF_BODY)
        ;

        $app['asset.queue.file']->add($js);

    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigFunctions()
    {
        return [
            'socialite' => ['twigSocialite', ['is_safe' => ['html']]]
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigPaths()
    {
        return ['templates'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultConfig()
    {
        return [
            'activation'                        => 'scroll',
            'template'                          => 'socialite.twig',
            'facebook_app_id'                   => '',
            'facebook_like_action'              => 'like',
            'facebook_like_colorscheme'         => 'light',
            'facebook_like_kid_directed_site'   => 'false',
            'facebook_like_show_faces'          => 'false',
            'facebook_like_layout'              => 'box_count',
            'facebook_like_width'               => '60',
            'facebook_follow_action'            => 'like',
            'facebook_follow_colorscheme'       => 'light',
            'facebook_follow_kid_directed_site' => 'false',
            'facebook_follow_show_faces'        => 'false',
            'facebook_follow_layout'            => 'box_count',
            'facebook_follow_width'             => '60',
            'facebook_facepile_max_rows'        => '1',
            'facebook_facepile_colorscheme'     => 'light',
            'facebook_facepile_size'            => 'medium',
            'facebook_facepile_count'           => 'true',
            'google_plus_share_annotation'      => 'vertical-bubble',
            'google_plus_share_size'            => 'medium',
            'google_plus_share_relationship'    => 'publisher',
            'google_plus_follow_annotation'     => 'vertical-bubble',
            'google_plus_follow_size'           => 'medium',
            'google_plus_follow_relationship'   => 'publisher',
            'google_plus_badge_layout'          => 'portrait',
            'google_plus_badge_width'           => '300',
            'google_plus_badge_theme'           => 'light',
            'google_plus_badge_photo'           => 'enabled',
            'google_plus_badge_tagline'         => 'enabled',
            'google_plus_badge_relationship'    => 'publisher',
            'twitter_handle'                    => '',
            'twitter_data_widget_id'            => '',
            'twitter_data_chrome'               => '',
            'twitter_link_text'                 => '',
            'twitter_follow_align'              => 'left',
            'twitter_follow_count'              => 'horizontal',
            'twitter_follow_size'               => 'medium',
            'twitter_share_align'               => 'left',
            'twitter_share_count'               => 'vertical',
            'twitter_share_size'                => 'medium',
            'twitter_mention_align'             => 'left',
            'twitter_mention_size'              => 'medium',
            'twitter_hashtag_align'             => 'left',
            'twitter_hashtag_size'              => 'medium',
            'pinterest_pinit_config'            => 'above',
            'pinterest_pinit_color'             => 'red',
            'pinterest_pinit_size'              => 'small',
            'pinterest_pinit_language'          => 'en',
            'pinterest_pinit_hover'             => 'on',
            'bufferapp_count'                   => 'vertical',
            'bufferapp_twitter_user'            => 'BoltCM',
            'github_user'                       => 'bolt',
            'github_repo'                       => 'bolt',
            'github_count'                      => 'true',
            'github_size'                       => 'large',
            'url'                               => null,
        ];
    }
}

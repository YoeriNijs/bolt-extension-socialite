<?php

namespace Bolt\Extension\Bolt\Socialite;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Socialite widget functions
 */
class Widget
{
    public function __construct()
    {

    }

    public function createWidget(\Bolt\Application $app, $config, $buttons)
    {
        $this->app = $app;
        $this->config = $config;

        // Store the record in config
        $this->getRecord();

        // We allow either a ('string') or (['an', 'array']) of parameters, so
        // for simplicity just make everything an array
        if (!is_array($buttons)) {
            $buttons = array($buttons => $buttons);
        }

        // Insert a <div><a> for each module called this time
        foreach ($buttons as $key => $value) {

            if (is_numeric($key) && method_exists($this, $value)) {
                $html = call_user_func(array($this, $value), false);
                return new \Twig_Markup($html, 'UTF-8');
            } elseif (method_exists($this, $key)) {
                $html = call_user_func(array($this, $key), $value);
                return new \Twig_Markup($html, 'UTF-8');
            }

        }
    }

    private function getRecord()
    {
        if (isset($this->record)) {
            return $this->record;
        }

        $globalTwigVars = $this->app['twig']->getGlobals('record');

        if (isset($globalTwigVars['record'])) {
            $this->record = $globalTwigVars['record'];
        } else {
            $this->record = false;
        }
    }

    private function BufferAppButton($args = false)
    {
        if (empty($this->config['bufferapp_count'])) {
            $this->config['bufferapp_count'] = 'vertical';
        }

        if (empty($this->config['bufferapp_twitter_user'])) {
            return 'Socialite setting bufferapp_twitter_user not set';
        }

        if (is_array($this->record->values['image'])) {
            $image = $this->app['paths']['rooturl'] . $this->app['paths']['files'] . $this->record->values['image']['file'];
        } else {
            $image = $this->app['paths']['rooturl'] . $this->app['paths']['files'] . $this->record->values['image'];
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'BufferAppButton',
            'text' => $this->record->values['title'],
            'url' => $this->config['url'],
            'count' => $this->config['bufferapp_count'],
            'via' => $this->config['bufferapp_twitter_user'],
            'picture' => $image
        ));
    }

    private function FacebookLike()
    {
        if (empty($this->config['facebook_like_action'])) {
            $this->config['facebook_like_action'] = 'like';
        }

        if (empty($this->config['facebook_like_colorscheme'])) {
            $this->config['facebook_like_colorscheme'] = 'light';
        }

        if (empty($this->config['facebook_like_kid_directed_site'])) {
            $this->config['facebook_like_kid_directed_site'] = 'false';
        }

        if (empty($this->config['facebook_like_show_faces'])) {
            $this->config['facebook_like_show_faces'] = 'false';
        }

        if (empty($this->config['facebook_like_layout'])) {
            $this->config['box_count'] = '';
        }

        if (empty($this->config['facebook_like_width'])) {
            $this->config[''] = '60';
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'FacebookLike',
            'url' => $this->config['url'],
            'title' => $this->record->values['title'],
            'action' => $this->config['facebook_like_action'],
            'colorscheme' => $this->config['facebook_like_colorscheme'],
            'kid_directed_site' => $this->config['facebook_like_kid_directed_site'],
            'showfaces' => $this->config['facebook_like_show_faces'],
            'layout' => $this->config['facebook_like_layout'],
            'width' => $this->config['facebook_like_width']
        ));
    }

    private function FacebookFollow($args = false)
    {
        if (empty($this->config['facebook_follow_action'])) {
            $this->config['facebook_follow_action'] = 'like';
        }

        if (empty($this->config['facebook_follow_colorscheme'])) {
            $this->config['facebook_follow_colorscheme'] = 'light';
        }

        if (empty($this->config['facebook_follow_kid_directed_site'])) {
            $this->config['facebook_follow_kid_directed_site'] = 'false';
        }

        if (empty($this->config['facebook_follow_show_faces'])) {
            $this->config['facebook_follow_show_faces'] = 'false';
        }

        if (empty($this->config['facebook_follow_layout'])) {
            $this->config[''] = 'box_count';
        }

        if (empty($this->config['facebook_follow_width'])) {
            $this->config[''] = '60';
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'FacebookFollow',
            'url' => $args,
            'action' => $this->config['facebook_follow_action'],
            'colorscheme' => $this->config['facebook_follow_colorscheme'],
            'kid_directed_site' => $this->config['facebook_follow_kid_directed_site'],
            'showfaces' => $this->config['facebook_follow_show_faces'],
            'layout' => $this->config['facebook_follow_layout'],
            'width' => $this->config['facebook_follow_width']
        ));
    }

    private function FacebookFacepile($args = false)
    {
        if (empty($this->config['facebook_facepile_max_rows'])) {
            $this->config['facebook_facepile_max_rows'] = '1';
        }

        if (empty($this->config['facebook_facepile_colorscheme'])) {
            $this->config['facebook_facepile_colorscheme'] = 'light';
        }

        if (empty($this->config['facebook_facepile_size'])) {
            $this->config['facebook_facepile_size'] = 'medium';
        }

        if (empty($this->config['facebook_facepile_count'])) {
            $this->config['facebook_facepile_count'] = 'true';
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'FacebookFacepile',
            'url' => $args,
            'maxrows' => $this->config['facebook_facepile_max_rows'],
            'colorscheme' => $this->config['facebook_facepile_colorscheme'],
            'size' => $this->config['facebook_facepile_size'],
            'count' => $this->config['facebook_facepile_count']
        ));

        //data-max-rows="2" data-colorscheme="light" data-size="small" data-show-count="true"
    }

    private function TwitterShare()
    {
        if (empty($this->config['twitter_share_align'])) {
            $this->config['twitter_share_align'] = 'left';
        }

        if (empty($this->config['twitter_share_count'])) {
            $this->config['twitter_share_count'] = 'vertical';
        }

        if (empty($this->config['twitter_share_size'])) {
            $this->config['twitter_share_size'] = 'medium';
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'TwitterShare',
            'title' => $this->record->values['title'],
            'url' => $this->config['url'],
            'align' => $this->config['twitter_share_align'],
            'count' => $this->config['twitter_share_count'],
            'size' => $this->config['twitter_share_size']
        ));
    }

    private function TwitterFollow()
    {
        if (empty($this->config['twitter_handle'])) {
            return 'Socilaite setting twitter_handle not set';
        }

        if (empty($this->config['twitter_follow_align'])) {
            $this->config['twitter_follow_align'] = 'left';
        }

        if (empty($this->config['twitter_follow_count'])) {
            $this->config['twitter_follow_count'] = 'vertical';
        }

        if (empty($this->config['twitter_follow_size'])) {
            $this->config['twitter_follow_size'] = 'medium';
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'TwitterFollow',
            'twitter_handle' => $this->config['twitter_handle'],
            'title' => $this->record->values['title'],
            'url' => $this->config['url'],
            'align' => $this->config['twitter_follow_align'],
            'count' => $this->config['twitter_follow_count'],
            'size' => $this->config['twitter_follow_size']
        ));
    }

    private function TwitterMention()
    {
        if (empty($this->config['twitter_handle'])) {
            return 'Socilaite setting twitter_handle not set';
        }

        if (empty($this->config['twitter_mention_align'])) {
            $this->config['twitter_mention_align'] = 'left';
        }

        if (empty($this->config['twitter_mention_size'])) {
            $this->config['twitter_mention_size'] = 'medium';
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'TwitterFollow',
            'twitter_handle' => $this->config['twitter_handle'],
            'title' => $this->record->values['title'],
            'url' => $this->config['url'],
            'align' => $this->config['twitter_mention_align'],
            'size' => $this->config['twitter_mention_size']
        ));
    }

    private function TwitterHashtag($args = false)
    {
        if (empty($this->config['twitter_mention_align'])) {
            $this->config['twitter_hashtag_align'] = 'left';
        }

        if (empty($this->config['twitter_mention_size'])) {
            $this->config['twitter_hashtag_size'] = 'medium';
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'TwitterHashtag',
            'hashtag' => $args,
            'title' => $this->record->values['title'],
            'url' => $this->config['url'],
            'align' => $this->config['twitter_hashtag_align'],
            'size' => $this->config['twitter_hashtag_size']
        ));
    }

    private function TwitterEmbed($args = false)
    {
        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'TwitterEmbed',
            'url' => $args
        ));
    }

    private function TwitterTimeline()
    {
        if (empty($this->config['twitter_handle'])) {
            return 'Socilaite setting twitter_handle not set';
        }

        if (empty($this->config['twitter_data_widget_id'])) {
            return 'Socilaite setting twitter_data_widget_id not set';
        }

        if (empty($this->config['twitter_data_chrome'])) {
            $this->config['twitter_data_chrome'] = 'noheader nofooter noborders noscrollbar transparent';
        }

        $twitter_handle = str_replace( '@', '', $this->config['twitter_handle'] );

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'TwitterTimeline',
            'twitter_handle' => $twitter_handle,
            'widget_id' => $this->config['twitter_data_widget_id'],
            'chrome' => $this->config['twitter_data_chrome']
        ));
    }

    private function GooglePlusFollow($args = false)
    {
        if (empty($this->config['google_plus_follow_size'])
            || $this->config['google_plus_follow_size'] == 'small') {
            $this->config['google_plus_follow_size'] = 15;
        } elseif ($this->config['google_plus_follow_size'] == 'medium') {
            $this->config['google_plus_follow_size'] = 20;
        } elseif ($this->config['google_plus_follow_size'] == 'large') {
            $this->config['google_plus_follow_size'] = 24;
        }

        if (empty($this->config['google_plus_follow_annotation'])) {
            $this->config['google_plus_follow_annotation'] = 'vertical-bubble';
        }

        if (empty($this->config['google_plus_follow_relationship'])) {
            $this->config['google_plus_follow_relationship'] = 'publisher';
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'GooglePlusFollow',
            'url' => $args,
            'annotation' => $this->config['google_plus_follow_annotation'],
            'height' => $this->config['google_plus_follow_size'],
            'rel' => $this->config['google_plus_follow_relationship']
        ));
    }

    private function GooglePlusOne()
    {
        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'GooglePlusOne',
            'url' => $this->config['url']
        ));
    }

    private function GooglePlusShare()
    {
        if (empty($this->config['google_plus_share_annotation'])) {
            $this->config['google_plus_share_annotation'] = 'vertical-bubble';
        }

        if ($this->config['google_plus_share_annotation'] == 'bubble'
        || $this->config['google_plus_share_annotation'] == 'vertical-bubble') {
            $this->config['google_plus_share_size'] = '';
        } else {
            if ($this->config['google_plus_share_size'] == 'small') {
                $this->config['google_plus_share_size'] = 15;
            } elseif ($this->config['google_plus_share_size'] == 'medium') {
                $this->config['google_plus_share_size'] = 20;
            } elseif ($this->config['google_plus_share_size'] == 'large') {
                $this->config['google_plus_share_size'] = 24;
            }
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'GooglePlusShare',
            'url' => $this->config['url'],
            'annotation' => $this->config['google_plus_share_annotation'],
            'height' => $this->config['google_plus_share_size']
        ));
    }

    private function GooglePlusBadge($args)
    {
        if (empty($this->config['google_plus_badge_layout'])) {
            $this->config['google_plus_badge_layout'] = 'portrait';
        }

        if (empty($this->config['google_plus_badge_width'])) {
            $this->config['google_plus_badge_width'] = '300';
        }

        if (empty($this->config['google_plus_badge_theme'])) {
            $this->config['google_plus_badge_theme'] = 'light';
        }

        if (empty($this->config['google_plus_badge_photo'])) {
            $this->config['google_plus_badge_photo'] = 'enabled';
        }

        if (empty($this->config['google_plus_badge_tagline'])) {
            $this->config['google_plus_badge_tagline'] = 'enabled';
        }

        if (empty($this->config['google_plus_badge_relationship'])) {
            $this->config['google_plus_badge_relationship'] = 'publisher';
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'GooglePlusBadge',
            'url' => $args,
            'layout' => $this->config['google_plus_badge_layout'],
            'width' => $this->config['google_plus_badge_width'],
            'theme' => $this->config['google_plus_badge_theme'],
            'showcoverphoto' => $this->config['google_plus_badge_photo'],
            'showtagline' => $this->config['google_plus_badge_tagline'],
            'rel' => $this->config['google_plus_badge_relationship'],
        ));
    }

    private function LinkedinShare()
    {
        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'LinkedinShare',
            'url' => $this->config['url'],
            'title' => $this->record->values['title']
        ));
    }

    private function LinkedinRecommend()
    {
        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'LinkedinRecommend',
            'url' => $this->config['url'],
            'title' => $this->record->values['title']
        ));
    }

    private function PinterestPinit()
    {
        if (empty($this->config['pinterest_pinit_color'])) {
            $this->config['pinterest_pinit_color'] = "red";
        }
        if (empty($this->config['pinterest_pinit_size']) || $this->config['pinterest_pinit_size'] = 'small') {
            $this->config['pinterest_pinit_size'] = "20";
        } elseif ($this->config['pinterest_pinit_size'] == 'large') {
            $this->config['pinterest_pinit_size'] = "28";
        }
        if (empty($this->config['pinterest_pinit_language'])) {
            $this->config['pinterest_pinit_language'] = "en";
        }
        if (empty($this->config['pinterest_pinit_hover'])) {
            $this->config['pinterest_pinit_hover'] = "on";
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'PinterestPinit',
            'lang' => $this->config['pinterest_pinit_language'],
            'color' => $this->config['pinterest_pinit_color'],
            'height' => $this->config['pinterest_pinit_size'],
            'config' => $this->config['pinterest_pinit_config']
        ));
    }
    /*
     private function SpotifyPlay()
     {
     $html = '
     <div class="social-buttons cf">

     </div>';

     return new \Twig_Markup($html, 'UTF-8');
     }

     private function HackerNewsShare()
     {
     $html = '
     <div class="social-buttons cf">

     </div>';

     return new \Twig_Markup($html, 'UTF-8');
     }
     */
    private function GitHubStar($args)
    {
        if (empty($args[0])) {
            $user = $this->config['github_user'];
        } else {
            $user = $args[0];
        }
        if (empty($args[1])) {
            $repo = $this->config['github_repo'];
        } else {
            $repo = $args[1];
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'GitHubStar',
            'user' => $user,
            'repo' => $repo,
            'count' => $this->config['github_count'],
            'size' => $this->config['github_size']
        ));
    }

    private function GitHubFork($args)
    {
        if (empty($args[0])) {
            $user = $this->config['github_user'];
        } else {
            $user = $args[0];
        }
        if (empty($args[1])) {
            $repo = $this->config['github_repo'];
        } else {
            $repo = $args[1];
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'GitHubFork',
            'user' => $user,
            'repo' => $repo,
            'count' => $this->config['github_count'],
            'size' => $this->config['github_size']
        ));
    }

    private function GitHubFollow($args)
    {
        if (empty($args)) {
            $user = $this->config['github_user'];
        } else {
            $user = $args;
        }

        return $this->app['render']->render($this->config['template'], array(
            'socialite' => 'GitHubFollow',
            'user' => $user,
            'count' => $this->config['github_count'],
            'size' => $this->config['github_size']
        ));
    }

    /*
     private function GitHubWatch($args)
     {
     if (empty($args[0])) {
     $user = $this->config['github_user'];
     } else {
     $user = $args[0];
     }
     if (empty($args[1])) {
     $repo = $this->config['github_repo'];
     } else {
     $repo = $args[1];
     }

     return $this->app['render']->render($this->config['template'], array(
     'socialite' => 'GitHubWatch',
     'user' => $user,
     'repo' => $repo,
     'count' => $this->config['github_count'],
     'size' => $this->config['github_size']
     ));
     }

     private function DzoneSubmit()
     {
     $html = '
     <div class="social-buttons cf">

     </div>';

     return new \Twig_Markup($html, 'UTF-8');
     }
     */
}

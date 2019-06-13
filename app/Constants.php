<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constants extends Model
{
    const INSTAGRAM = 'INSTAGRAM';
    const VK = 'VK';
    const FAKE = 'FAKE';
    const NOT_SET = 'NOT_SET';

    // link, quantity
    const SERVICE_INSTAGRAM_SUBS = 'SERVICE_INSTAGRAM_SUBS'; // link = login

    const SERVICE_INSTAGRAM_LIKES = 'SERVICE_INSTAGRAM_LIKES';  // link = media
    const SERVICE_INSTAGRAM_LIKES_LIVE = 'SERVICE_INSTAGRAM_LIKES_LIVE'; // link = login

    const SERVICE_INSTAGRAM_VIEWS_IGTV = 'SERVICE_INSTAGRAM_VIEWS_IGTV'; // link = media
    const SERVICE_INSTAGRAM_VIEWS_VIDEO = 'SERVICE_INSTAGRAM_VIEWS_VIDEO'; // link = media
    const SERVICE_INSTAGRAM_VIEWS_VIDEO_IMPRESSIONS = 'SERVICE_INSTAGRAM_VIEWS_VIDEO_IMPRESSIONS';// link = media
    const SERVICE_INSTAGRAM_VIEWS_STORY = 'SERVICE_INSTAGRAM_VIEWS_STORY'; // link = login
    const SERVICE_INSTAGRAM_VIEWS_SHOW_IMPRESSIONS = 'SERVICE_INSTAGRAM_VIEWS_SHOW_IMPRESSIONS'; // link = media
    const SERVICE_INSTAGRAM_VIEWS_LIVE = 'SERVICE_INSTAGRAM_VIEWS_LIVE'; // link = login

    // username, min, max, posts, delay
    const SERVICE_INSTAGRAM_AUTO_LIKES = 'SERVICE_INSTAGRAM_AUTO_LIKES';
    const SERVICE_INSTAGRAM_AUTO_LIKES_VIEWS_IMPRESSIONS = 'SERVICE_INSTAGRAM_AUTO_LIKES_VIEWS_IMPRESSIONS';
    const SERVICE_INSTAGRAM_AUTO_VIEWS = 'SERVICE_INSTAGRAM_AUTO_VIEWS';

    const subclasses = [
        self::SERVICE_INSTAGRAM_SUBS => \App\Orders\Subscribers::class,

        self::SERVICE_INSTAGRAM_LIKES => \App\Orders\Likes::class,
        self::SERVICE_INSTAGRAM_LIKES_LIVE => \App\Orders\LikesLive::class,

        self::SERVICE_INSTAGRAM_VIEWS_IGTV => \App\Orders\ViewsIGTV::class,
        self::SERVICE_INSTAGRAM_VIEWS_VIDEO => \App\Orders\ViewsVideo::class,
        self::SERVICE_INSTAGRAM_VIEWS_VIDEO_IMPRESSIONS => \App\Orders\ViewsVideoImpressions::class,
        self::SERVICE_INSTAGRAM_VIEWS_STORY => \App\Orders\ViewsStory::class,
        self::SERVICE_INSTAGRAM_VIEWS_SHOW_IMPRESSIONS => \App\Orders\ViewsShowImpressions::class,
        self::SERVICE_INSTAGRAM_VIEWS_LIVE => \App\Orders\ViewsLive::class,

        self::SERVICE_INSTAGRAM_AUTO_LIKES => \App\Orders\AutoLikes::class,
        self::SERVICE_INSTAGRAM_AUTO_LIKES_VIEWS_IMPRESSIONS => \App\Orders\AutoLikesViewsImpressions::class,
        self::SERVICE_INSTAGRAM_AUTO_VIEWS => \App\Orders\AutoViews::class,
    ];

    const GROUP_LIKES = 'GROUP_LIKES';
    const GROUP_VIEWS = 'GROUP_VIEWS';
    const GROUP_SUBS = 'GROUP_SUBS';
    const GROUP_OTHER = 'GROUP_OTHER';
}

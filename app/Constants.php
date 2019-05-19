<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constants extends Model
{
    const INSTAGRAM = 'INSTAGRAM';
    const VK = 'VK';
    const FAKE = 'FAKE';
    const NOT_SET = 'NOT_SET';

    const SERVICE_INSTAGRAM_LIKES = 'SERVICE_INSTAGRAM_LIKES';
    const SERVICE_INSTAGRAM_SUBS = 'SERVICE_INSTAGRAM_SUBS';
    const SERVICE_INSTAGRAM_VIDEO_VIEWS = 'SERVICE_INSTAGRAM_VIDEO_VIEWS';

    const SERVICE_INSTAGRAM_VIEWS = 'SERVICE_INSTAGRAM_VIEWS';
    const SERVICE_INSTAGRAM_AUTOLIKES = 'SERVICE_INSTAGRAM_AUTOLIKES';
    const SERVICE_INSTAGRAM_AUTOVIEWS = 'SERVICE_INSTAGRAM_AUTOVIEWS';
    const SERVICE_INSTAGRAM_STORIES_VIEWS = 'SERVICE_INSTAGRAM_STORIES_VIEWS';

    const SERVICE_AUTO_FAKE = 'SERVICE_AUTO_FAKE';

    const subclasses = [
        self::SERVICE_INSTAGRAM_LIKES => \App\Orders\Likes::class,
        self::SERVICE_INSTAGRAM_SUBS => \App\Orders\Subscribers::class,
        self::SERVICE_INSTAGRAM_VIDEO_VIEWS => \App\Orders\VideoViews::class,

        self::SERVICE_AUTO_FAKE => \App\Orders\AutoFake::class,
    ];
}

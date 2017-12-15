<?php

namespace Baytek\Laravel\Content\Types\Newsletter\Policies;

use Baytek\Laravel\Content\Policies\GeneralPolicy;

use Illuminate\Auth\Access\HandlesAuthorization;

class NewsletterPolicy extends GeneralPolicy
{
    public $contentType = 'Newsletter';
}

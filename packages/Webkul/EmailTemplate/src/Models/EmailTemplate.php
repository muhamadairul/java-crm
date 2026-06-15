<?php

namespace Webkul\EmailTemplate\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Traits\BelongsToCompany;
use Webkul\EmailTemplate\Contracts\EmailTemplate as EmailTemplateContract;

class EmailTemplate extends Model implements EmailTemplateContract
{
    use BelongsToCompany;

    protected $fillable = [
        'name',
        'subject',
        'content',
    ];
}

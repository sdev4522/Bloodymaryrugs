<?php

namespace Botble\ItemInquiry\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $table = 'product_inquiries';

    protected $fillable = ['product_id', 'name', 'phone', 'email', 'message'];
}

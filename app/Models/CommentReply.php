<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    use HasFactory;

    protected $fillable = ['comment_id', 'user_name', 'comment_reply'];
    protected $table = 'comment_replies';

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}

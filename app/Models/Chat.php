<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends Model
{
  use HasFactory;

  protected $table = "chats";
  protected $guarded = ['id'];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'chat_id');
  }

  public function messages(): HasMany
  {
    return $this->hasMany(ChatMessage::class, 'chat_id');
  }

  public function lastMessage(): HasOne
  {
    return $this->hasOne(ChatMessage::class, 'chat_id')->latest('updated_at');
  }

}

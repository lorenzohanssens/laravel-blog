<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Follow
 *
 * @property int $id
 * @property int $user_id
 * @property int $followeduser
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Follow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Follow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Follow query()
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereFolloweduser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereUserId($value)
 * @property-read \App\Models\User $userBeingFollowed
 * @property-read \App\Models\User $userThatIsFollowing
 * @mixin \Eloquent
 */
class Follow extends Model
{
    use HasFactory;

    public function userThatIsFollowing() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function userBeingFollowed() {
        return $this->belongsTo(User::class, 'followeduser');
    }
}

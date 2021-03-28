<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
   use HasFactory, Notifiable;

   public $incrementing = false;
   protected $primaryKey = 'id';
   protected $keyType = 'string';

   /**
     * The attributes that are mass assignable.
     *
     * @var array
   */
   protected $fillable = [
      'id',
      'name',
      'username',
      'email',
      'address',
      'password',
      'role'
   ];
} 

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guest extends Model implements MustVerifyEmail


{
    protected $fillable=[
		'first_name',
		'last_name',
		'education',
		'city_id',
        'id',
        'email',
        'password',
        'phone',
        'verification_code',
       // 'question_list_id'
        ];
		public function cvs(){
			$this->hasMany(Cv::class);
		}
    public $timestamps = false;
    use HasFactory;
	/**
	 * Determine if the user has verified their email address.
	 * @return bool
	 */
	
	/**
	 * Determine if the user has verified their email address.
	 * @return bool
	 */
	public function hasVerifiedEmail() {
	}
	
	/**
	 * Mark the given user's email as verified.
	 * @return bool
	 */
	public function markEmailAsVerified() {
	}
	
	/**
	 * Send the email verification notification.
	 * @return void
	 */
	public function sendEmailVerificationNotification() {
	}
	
	/**
	 * Get the email address that should be used for verification.
	 * @return string
	 */
	public function getEmailForVerification() {
	}
}

<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;



Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware(['guest','settings'])
                ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware(['guest','settings']);



Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware(['guest','settings'])
                ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware(['guest','settings'])
                ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware(['auth','settings'])
                ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth', 'signed', 'throttle:6,1','settings'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1','settings'])
                ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware(['auth','settings'])
                ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware(['auth','settings']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware(['auth','settings'])
                ->name('logout');


Route::get('/forgot-password',[PasswordResetLinkController::class, 'create'])
                ->name('password.request');
Route::post('/password-otp',[PasswordResetLinkController::class, 'save'])
                ->name('password.otp');           
Route::get('/password-otp/{slug}',[PasswordResetLinkController::class, 'otp'])
                ->name('email.otp');  
Route::post('/password-otpcheck',[PasswordResetLinkController::class,'otpcheck']) 
                ->name('otp.check'); 
Route::get('/password-create/{slug}',[PasswordResetLinkController::class,'changepassword'])
                ->name('password.create');                  
Route::post('/password-change',[PasswordResetLinkController::class,'savepassword'])
                ->name('password.changed');                                          
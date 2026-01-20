<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::livewire('templates', 'pages::templates')->name('templates');
Route::livewire('template/edit/{id}', 'pages::template.edit')->name('templates.edit');
Route::livewire('leads', 'pages::leads')->name('leads');
Route::livewire('leads/view/{id}', 'pages::leads.view')->name('leads.view');

require __DIR__.'/settings.php';

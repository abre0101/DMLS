<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Document;
use App\Policies\DocumentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
   
    \App\Models\Document::class => \App\Policies\DocumentPolicy::class,


        Letter::class => LetterPolicy::class,
        Report::class => ReportPolicy::class, 
     \App\Models\Letter::class => \App\Policies\LetterPolicy::class, ];

    public function boot()
    {
        $this->registerPolicies();
    }


}
<?php

namespace App\Providers;

use App\Events\AtsReportGenerated;
use App\Events\CvGenerated;
use App\Events\SupportConversationUpdated;
use App\Listeners\LogAtsReport;
use App\Listeners\LogSupportConversationUpdate;
use App\Listeners\TriggerAtsAnalysis;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CvGenerated::class => [
            TriggerAtsAnalysis::class,
        ],
        AtsReportGenerated::class => [
            LogAtsReport::class,
        ],
        SupportConversationUpdated::class => [
            LogSupportConversationUpdate::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}

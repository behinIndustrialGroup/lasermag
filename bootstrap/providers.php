<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\BroadcastServiceProvider::class,
    Barryvdh\TranslationManager\ManagerServiceProvider::class,
    BehinFileControl\BehinFileControlProvider::class,
    BehinInit\BehinInitProvider::class,
    BehinLogging\ServiceProvider::class,
    BehinUserRoles\UserRolesServiceProvider::class,
    Behin\SimpleWorkflowReport\SimpleWorkflowReportProvider::class,
    Behin\SimpleWorkflow\SimpleWorkflowProvider::class,
    Behin\Sms\SmsProvider::class,
    Maatwebsite\Excel\ExcelServiceProvider::class,
    MyFormBuilder\FormBuilderServiceProvider::class,
    UserProfile\UserProfileProvider::class,
    BaleBot\BaleBotProvider::class,
    Behin\Ami\AmiServiceProvider::class,
    UserNotifications\Providers\UserNotificationsServiceProvider::class,
];

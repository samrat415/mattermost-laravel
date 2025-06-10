<?php

namespace Samrat415\MattermostLaravel;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\App;
use Samrat415\MattermostLaravel\Commands\MattermostLaravelCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Throwable;

class MattermostLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('mattermost-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_mattermost_laravel_table')
            ->hasCommand(MattermostLaravelCommand::class);
    }

    public function bootingPackage()
    {
        // Only in web requests (not CLI or tests)
        if (! App::runningInConsole()) {
            $this->app->make(ExceptionHandler::class)->renderable(function (Throwable $e, $request) {
                if (
                    config('mattermost-laravel.enabled') &&
                    ! $request->expectsJson() &&
                    ! $request->is('api/*')
                ) {
                    app(MattermostLaravel::class)->alert($e, $request);
                    if (config('mattermost-laravel.redirect_back')) {
                        return back()->with('alert', [
                            'type' => 'danger',
                            'title' => 'Error!',
                            'message' => App::environment('production')
                                ? 'An error was encountered. Please contact your system administrator.'
                                : $e->getMessage(),
                        ]);
                    }
                }
                if ($request->expectsJson() || $request->is('api/*')) {
                    app(MattermostLaravel::class)->alert($e, $request);
                    $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                    return response()->json([
                        'error' => App::environment('production')
                            ? 'An error was encountered. Please contact your system administrator.'
                            : $e->getMessage(),
                    ], $statusCode);
                }

                return null;
            });
        }
    }
}

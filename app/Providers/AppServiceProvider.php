<?php

namespace App\Providers;

use App\Models\AcademicYear;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Paginator::useBootstrapFive();

    View::composer('*', function ($view) {
      $currentYear = AcademicYear::where('is_current', true)->first();
      $currentYearID = $currentYear ? $currentYear->id : null;

      $sessionYearID = session('newCurrentYearID') ?? $currentYearID;

      $yearCanDo = false;
      if ($currentYearID && ($sessionYearID == $currentYearID)) {
        $yearCanDo = true;
      }

      $view->with([
        'yearCanDo' => $yearCanDo
      ]);
    });
  }
}

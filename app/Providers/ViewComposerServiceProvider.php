<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\Models\GeneralModel;
use App\Models\Menu;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use DB;
use Route;

class ViewComposerServiceProvider extends ServiceProvider
{
    protected $gm;

    public function __construct()
    {
        $this->gm = new GeneralModel();
    }
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerLayout();
        $this->pageTitleLayout();
    }

    protected function registerLayout()
    {
        View::composer('layouts.layout.aside._menu', function ($view) {
            $view->with('assidemenu', $this->gm->getassidemenu());
        });
    }
    
    protected function pageTitleLayout()
    {
        $data['title'] = $this->gm->getpagetitle();
        $data['breadcrumb'] = $this->gm->getpagetitle();
        View::composer('layouts.layout.page-title._default', function ($view) use($data){
            $view->with($data);
        });
    }
    
}

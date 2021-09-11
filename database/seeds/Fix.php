<?php
namespace Database\Seeders;
use App\Http\Middleware\RunUpdater;
use Illuminate\Database\Seeder;



class Fix extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app(RunUpdater::class)->updateTo110();
        app(RunUpdater::class)->updateTo120();
        app(RunUpdater::class)->updateTo130();
        app(RunUpdater::class)->updateTo140();
        app(RunUpdater::class)->updateTo150();
        app(RunUpdater::class)->updateTo160();
        app(RunUpdater::class)->updateTo170();
        app(RunUpdater::class)->updateTo180();
        app(RunUpdater::class)->updateTo190();
        app(RunUpdater::class)->updateTo200();
        app(RunUpdater::class)->updateTo210();









    }
}

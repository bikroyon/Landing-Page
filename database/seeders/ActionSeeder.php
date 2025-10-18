<?php
namespace Database\Seeders;

use App\Models\Action;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    public function run(): void
    {
        $actions = ['view', 'create', 'edit', 'delete', 'print', 'export'];

        foreach ($actions as $action) {
            Action::create(['name' => $action]);
        }
    }
}

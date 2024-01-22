<?php

namespace Database\Seeders;

use App\Models\Modules;
use App\Models\Permissions;
use App\Models\Roles;
use App\Services\MiscService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleSeeder extends Seeder
{
    private $miscService;  // Private property to store the MiscService instance

    public function __construct(MiscService $miscService)
    {
        $this->miscService = $miscService;  // Inject the MiscService instance into the controller
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Modules::truncate();
        Permissions::truncate();

        $json = File::get("database/data/modules.json");
        $moduleData = json_decode($json);

        $count = 1; // initialize loop counter
        $roles = Roles::where('status', 'ACTIVE')->get();
        $parentArrangeCount = 1;

        foreach ($moduleData as $parent) {
            // parent module
            $modID = $this->miscService->autoGenCode($count, "MOD");

            $module = Modules::create([
                "modID" => $modID,
                "modName" => Str::slug($parent->modLabel),
                "arrange" => $parentArrangeCount,
                "modLabel" => $parent->modLabel,
                "hasChild" => property_exists($parent, 'childModules') ? 1 : 0,
                "isChild" =>  0,
                "modStatus" => 1,
                "modIcon" => $parent->modIcon,
                "modGroup" => $parent->modGroup
            ]);

            // Create permissions for each active role
            foreach ($roles as $role) {
                Permissions::create([
                    "role" => $role->id,
                    "modID" => $modID,
                    "modCreate" => $role->id == 1 ? 1 : 0,
                    "modRead" => $role->id == 1 ? 1 : 0,
                    "modUpdate" => $role->id == 1 ? 1 : 0,
                    "modDelete" => $role->id == 1 ? 1 : 0,
                    "modReport" => $role->id == 1 ? 1 : 0,
                ]);
            }

            // Write the module name to a file
            if (!property_exists($parent, 'childModules')) {
                // $routeCode = "Route::get('/" . Str::slug($parent->modLabel) . "', [RouteController::class, '" . Str::slug($parent->modLabel) . "'])->name('" . Str::slug($parent->modLabel) . "');" . PHP_EOL;
                // $file = base_path('routes/web.php');
                // file_put_contents($file, $routeCode, FILE_APPEND);
            }

            $childArrangeCount = 1;
            if (property_exists($parent, 'childModules')) {
                foreach ($parent->childModules as $child) {
                    $modID = $this->miscService->autoGenCode($count, "MOD");
                    $count++; // increment loop counter

                    $childMod = Modules::create([
                        "modID" => $modID,
                        "modName" => Str::slug($child->modLabel),
                        "arrange" => $childArrangeCount,
                        "modLabel" => $child->modLabel,
                        "pmodID" => $module->modID,
                        "hasChild" => 0,
                        "isChild" => 1,
                        "modStatus" => 1
                    ]);

                    // Create permissions for each active role
                    foreach ($roles as $role) {
                        Permissions::create([
                            "role" => $role->id,
                            "modID" => $childMod->modID,
                            "modCreate" => $role->id == 1 ? 1 : 0,
                            "modRead" => $role->id == 1 ? 1 : 0,
                            "modUpdate" => $role->id == 1 ? 1 : 0,
                            "modDelete" => $role->id == 1 ? 1 : 0,
                            "modReport" => $role->id == 1 ? 1 : 0,
                        ]);
                    }

                    // $routeCode = "Route::get('/" . Str::slug($child->modLabel) . "', [RouteController::class, '" . Str::slug($child->modLabel) . "'])->name('" . Str::slug($child->modLabel) . "');" . PHP_EOL;
                    // $file = base_path('routes/web.php');
                    // file_put_contents($file, $routeCode, FILE_APPEND);
                    $childArrangeCount++;
                }
            }

            $parentArrangeCount++;
        }
    }
}

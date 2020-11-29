<?php


namespace Modules\Admin\src\Sidebar;

use Maatwebsite\Sidebar\Sidebar;
use Maatwebsite\Sidebar\Menu;
use CoreCms;

class AdminSidebar implements Sidebar
{
    /**
     * @var Menu
     */
    protected $menu;

    /**
     * @param Menu $menu
     */
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    public function build()
    {
        $modules = CoreCms::getModules();
        foreach ($modules as $module_name => $module) {
            if (!$module->isEnabled()) {
                continue;
            }
            $class_name = "\Modules\/$module_name\Events\Sidebar\SidebarExtender";
            $class_name = str_replace('/', '', $class_name);
            if (class_exists($class_name)) {
                $extender = new $class_name;
                $this->menu->add(
                    $extender->extendWith($this->menu)
                );
            }
        }
    }

    public function getMenu()
    {
        $this->build();
        return $this->menu;
    }
}

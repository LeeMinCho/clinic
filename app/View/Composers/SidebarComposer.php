<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Screen;
use App\Models\Menu;
use App\Models\User;

class SidebarComposer
{
    public function compose(View $view)
    {
        $menuUser = Menu::with(['users'])
            ->distinct()
            ->whereHas('users', function ($query) {
                return $query->where('users.id', auth()->user()->id);
            })
            ->get();

        $menuId = [];
        if ($menuUser) {
            foreach ($menuUser as $mu) {
                $menuId[] = $mu->id;
            }
        }

        $primaryScreen = Screen::with(['menus'])
            ->whereHas('menus', function ($query) use ($menuId) {
                return $query->whereIn('menus.id', $menuId);
            })
            ->where('is_menu', 1)
            ->where('screen_id', null)
            ->orderBy('screen', 'ASC')
            ->get();
        $sidebarHtml = '';
        if ($primaryScreen) {
            foreach ($primaryScreen as $ps) {
                $subMenu = Screen::with(['menus'])
                    ->whereHas('menus', function ($query) use ($menuId) {
                        return $query->whereIn('menus.id', $menuId);
                    })
                    ->where('is_sub_menu', 1)
                    ->where('screen_id', $ps->id)
                    ->count();

                $menuOpen = false;
                if ($subMenu > 0) {
                    $url = $this->getSubMenu($ps->id, $menuId);
                    if (in_array(request()->path(), $url)) {
                        $menuOpen = true;
                    }
                } else {
                    if (request()->path() == $ps->url) {
                        $menuOpen = true;
                    }
                }

                $sidebarHtml .= '<li class="nav-item ' . ($menuOpen ? 'menu-open' : '') . '">
                    <a href="' . ($ps->url ? url($ps->url) : "#") . '" class="nav-link ' . ($menuOpen ? 'active' : '') . '">' . ($ps->icon ? '<i class="' . $ps->icon . '"></i>' : '') . '
                        <p>' . $ps->screen . ' ' .  ($subMenu > 0 ? '<i class="right fas fa-angle-left"></i>' : '') . '</p>
                    </a>' . ($subMenu ? $this->createSubMenu($ps->id, $menuId) : "") . '
                </li>';
            }
        }

        $view->with('menuSidebar', $sidebarHtml);
    }

    private function getSubMenu($id, $menuId, $item = array())
    {
        $subMenu = Screen::with(['menus'])
            ->whereHas('menus', function ($query) use ($menuId) {
                return $query->whereIn('menus.id', $menuId);
            })
            ->where('is_sub_menu', 1)
            ->where('screen_id', $id)
            ->get();

        if ($subMenu) {
            foreach ($subMenu as $sm) {
                $item[] = $sm->url;
                $item = array_merge($item, $this->getSubMenu($sm->id, $menuId));
            }
        }

        return $item;
    }

    private function createSubMenu($id, $menuId, $sidebarHtml = '')
    {
        $subMenu = Screen::with(['menus'])
            ->whereHas('menus', function ($query) use ($menuId) {
                return $query->whereIn('menus.id', $menuId);
            })
            ->where('is_sub_menu', 1)
            ->where('screen_id', $id)
            ->get();

        if ($subMenu->count() > 0) {
            $sidebarHtml .= "<ul class='nav nav-treeview'>";
            foreach ($subMenu as $sm) {
                $child = $this->createSubMenu($sm->id, $menuId);
                $sidebarHtml .= "<li class='nav-item'>
                    <a href='" . ($sm->url ? url($sm->url) : "#") . "' class='nav-link " . (request()->path() == $sm->url ? 'active' : '') . "'>" . ($sm->icon ? '<i class="' . $sm->icon . '"></i>' : '') . "
                        <p>" . $sm->screen . " " . (strval($child) != '' ? '<i class="right fas fa-angle-left"></i>' : '') .  "</p>
                    </a>" . $child . "
                </li>";
            }
            $sidebarHtml .= "</ul>";
        }

        return $sidebarHtml;
    }
}

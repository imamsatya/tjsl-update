<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route;
use Exception;
use Auth;
use DB;

use App\Models\Menu;
use App\Models\User;

class GeneralModel extends Model
{

	public function getparentmenu($search)
	{
		return Menu::where('label', 'ilike', '%' . $search . '%')->orderBy('parent_id', 'asc')->orderBy('order', 'asc')->get();
	}

	public function getpagetitle()
	{
		$route = Route::currentRouteName();
		$menu = Menu::where('route_name', 'ilike', '%' . $route . '%')->first();
		if ($menu) {
			$title = $menu->label;
		} else {
			$title = 'Dashboard';
		}
		return $route;
	}

	public function getassidemenu()
	{
		try {
			$html = $this->getrecursivemenu(0, Menu::where('status', true)->orderBy('order', 'ASC')->get(), User::find((int)Auth::user()->id)->getmenuaccess());
			// $html = $this->getrecursivemenu(0, Menu::where('status', true)->orderBy('order', 'ASC')->get(), User::find((int)16)->getmenuaccess());
			return $html;
		} catch (Exception $e) {
		}
	}

	protected function setsvgproperty($icon)
	{
		if (empty($icon)) {
			$iconsvg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <circle fill="#000000" cx="5" cy="12" r="2"/>
        <circle fill="#000000" cx="12" cy="12" r="2"/>
        <circle fill="#000000" cx="19" cy="12" r="2"/>
    </g>
</svg>';
		} else {
			$iconsvg = $icon;
		}
		return '<span class="kt-menu__link-icon">' . $iconsvg . '</span>';
	}

	protected function getrecursivemenu($parent_id, $menu, $data)
	{
		$html = '';
		$result = $menu->where('parent_id', (int)$parent_id)->sortBy('order');

		foreach ($result as $value) {
			$child = $menu->where('parent_id', (int)$value->id)->sortBy('order');
			$childData = $data->where('parent_id', (int)$value->id)->sortBy('order');

			$routing = $value->route_name != '#' ? (Route::has($value->route_name) ? route($value->route_name) : 'javascript:;') : '#';

			if ((bool)$child->count() && (bool)$childData->count()) {
				//jika ada child
				$class = (bool)$menu->where('parent_id', (int)$value->id)->where('route_name', Route::currentRouteName())->count() ? ' show' : '';
				$html .= '<div data-kt-menu-trigger="click" class="menu-item menu-accordion' . $class . '">';

				$html .= '<span class="menu-link">';
				$html .= '<span class="menu-icon"><i class="' . $value->icon . ' fs-3"></i></span>';
				$html .= '<span class="menu-title">' . $value->label . '</span>';
				$html .= '<span class="menu-arrow"></span>';
				$html .= '</span>';

				$html .= '<div class="menu-sub menu-sub-accordion">';
				$html .= $this->getrecursivemenu((int)$value->id, $menu, $data);
				$html .= '</div>';

				$html .= '</div>';
			} else {
				//jika tidak ada child
				if ((bool)$data->where('id', (int)$value->id)->count()) {
					$class = '';
					if (Route::currentRouteName() == 'home'  && $value->route_name == 'dashboard.index') {
						$class = 'active';
					} else if (Route::currentRouteName() === $value->route_name) {
						$class = 'active';
					}
					$icon = $value->icon;
					if (!$icon) {
						$icon = 'bullet bullet-dot';
					}
					$html .= '<div class="menu-item">';
					$html .= '<a class="menu-link ' . $class . '" href="' . $routing . '">';
					$html .= '<span class="menu-icon"><i class="' . $icon . '  fs-3"></i>';
					$html .= '</span>';
					$html .= '<span class="menu-title">' . $value->label . '</span>';
					$html .= '</a>';
					$html .= '</div>';
				}
			}
		}
		return $html;
	}
}

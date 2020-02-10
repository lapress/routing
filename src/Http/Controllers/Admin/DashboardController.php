<?php

namespace LaPress\Routing\Http\Controllers\Admin;

use Illuminate\Http\Request;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class DashboardController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Request $request)
    {
        // need trail '/'
        if (!ends_with($request->getPathInfo(), '/')) {
            // make valid url
            $url = $request->getSchemeAndHttpHost().$request->getBaseUrl().$request->getPathInfo().'/';

            $qs = $request->getQueryString();
            if ($qs !== null) {
                $url .= '?'.$qs;
            }

            // redirect to valid url
            return redirect()->to($url);
        }

        return $this->script()->runAdminWithMenu('index.php');
    }
}

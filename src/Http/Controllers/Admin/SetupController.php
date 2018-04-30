<?php

namespace LaPress\Routing\Http\Controllers\Admin;

use Parsedown;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class SetupController extends BaseController
{
    /**
     * @var Parsedown
     */
    private $parsedown;

    /**
     * @param Parsedown $parsedown
     */
    public function __construct(Parsedown $parsedown)
    {
        $this->parsedown = $parsedown;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $prefix = 'messages.disabled_feature.setup_config';

        return view('disabled-feature', [
            'title'   => trans($prefix.'.title'),
            'message' => trans($prefix.'.message'),
            'body'    => $this->parsedown->text(trans($prefix.'.body')),
        ]);
    }
    
    /**
     * @return \Illuminate\View\View
     */
    public function update()
    {
        $prefix = 'messages.disabled_feature.setup_config';

        return view('disabled-feature', [
            'title' => trans($prefix . '.title'),
            'message' => trans($prefix . '.message'),
            'body' => $this->parsedown->text(trans($prefix . '.body')),
        ]);
    }    
}
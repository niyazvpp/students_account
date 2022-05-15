<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Table extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $headers;

    public $body;

    public $tr_attributes;

    public function __construct($headers, $body, $tr_attributes = '')
    {
        $this->headers = $headers;
        $this->body = $body;
        $this->tr_attributes = $tr_attributes;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table');
    }
}

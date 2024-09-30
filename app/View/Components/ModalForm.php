<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalForm extends Component
{
    public $id;
    public $title;
    public $action;
    public $submitLabel;
    public $method;
    public $showFooter;

    /**
     * @param $id
     * @param $title
     * @param $action
     * @param $submitLabel
     * @param $method
     * @param $showFooter
     */
    public function __construct($id, $title, $action, $submitLabel = 'Save', $method = 'POST', $showFooter = true)
    {
        $this->id = $id;
        $this->title = $title;
        $this->action = $action;
        $this->submitLabel = $submitLabel;
        $this->method = $method;
        $this->showFooter = $showFooter;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal-form');
    }
}

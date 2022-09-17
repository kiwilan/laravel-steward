<?php

namespace Kiwilan\Steward\Components;

use Illuminate\View\Component;

class FieldCheckbox extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name = 'checkbox',
        public string $label = '',
        public bool $checked = false,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Closure|\Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('steward::components.field.checkbox');
    }
}

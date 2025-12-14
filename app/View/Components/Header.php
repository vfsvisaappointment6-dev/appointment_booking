<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Header extends Component
{
    /**
     * The logo text.
     *
     * @var string
     */
    public $logoText;

    /**
     * Create a new component instance.
     *
     * @param string $logoText
     * @return void
     */
    public function __construct($logoText = '3_Aura')
    {
        $this->logoText = $logoText;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.header');
    }
}

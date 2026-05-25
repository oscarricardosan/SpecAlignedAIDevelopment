<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Queue a toast message for the next response.
     */
    protected function toast(string $message, string $type = 'success'): void
    {
        session()->flash('toast', ['text' => $message, 'type' => $type]);
    }
}

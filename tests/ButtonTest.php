<?php

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Kiwilan\Steward\Components\Button;
use Kiwilan\Steward\Tests\TestCase;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use Spatie\Snapshots\MatchesSnapshots;

class ButtonTest extends TestCase
{
    use InteractsWithViews;

    /** @test */
    public function can_be_render()
    {
        // $renderedView = (string)$this->blade(
        //     <<<BLADE
        //     <x-markdown>
        //     # My title

        //     This is a [link to our website](https://spatie.be)

        //     ```php
        //     echo 'Hello world';
        //     ```
        //     </x-markdown>
        //     BLADE
        // );

        // $this->assert($renderedView);

        $slot = 'Submit';

        $component = $this->blade(
            "<x-steward-button>{$slot}</x-steward-button>",
            ['name' => 'Taylor']
        );

        $component->assertSeeText($slot);
        // $component->assertSee('type="\button\"');
    }

    /** @test */
    public function can_be_button()
    {
        $component = $this->blade(
            "<x-steward-button>Submit</x-steward-button>",
        );

        $component->assertSee('button');
    }

    /** @test */
    public function can_be_link()
    {
        $view = $this->component(Button::class, [
            'href' => '/',
        ]);

        $view->assertSee('href');
    }

    /** @test */
    public function can_be_external_link()
    {
        $view = $this->component(Button::class, [
            'href' => '/',
            'external' => true,
        ]);

        $view->assertSee('href');
        $view->assertSee('_blank');
    }
}

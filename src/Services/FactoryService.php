<?php

namespace Kiwilan\Steward\Services;

use Faker\Generator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Kiwilan\Steward\Services\FactoryService\FactoryBuilder;
use Kiwilan\Steward\Services\FactoryService\FactoryMedia;

class FactoryService
{
    public function __construct(
        public Generator $faker,
        public ?FactoryMedia $media = null,
        protected ?string $path = null,
    ) {
    }

    public static function make(string|\UnitEnum|null $media_path = null): self
    {
        $faker = \Faker\Factory::create();
        $service = new FactoryService($faker);
        $service->setFactoryMedia($media_path);

        return $service;
    }

    public function mdParagraphs(int $min = 1, int $max = 5): string
    {
        $bold = "**{$this->faker->sentence()}**";
        $italic = "*{$this->faker->sentence()}*";
        $code = "`{$this->faker->words(asText: true)}`";
        $link = "[{$this->faker->sentence()}]({$this->faker->url()})";
        $image = "  ![{$this->faker->sentence()}]({$this->faker->imageUrl()})  ";

        $html = [];
        if ($this->faker->boolean(25)) {
            $html[] = $bold;
        }
        if ($this->faker->boolean(25)) {
            $html[] = $italic;
        }
        if ($this->faker->boolean(25)) {
            $html[] = $code;
        }
        if ($this->faker->boolean(25)) {
            $html[] = $link;
        }
        if ($this->faker->boolean(25)) {
            $html[] = $image;
        }

        for ($k = 0; $k < $this->faker->numberBetween($min, $max); $k++) {
            $paragraph = $this->faker->sentence();
            $html[] = "{$paragraph}";
        }

        for ($k = 0; $k < $this->faker->numberBetween($min, $max); $k++) {
            $paragraph = $this->faker->paragraph();
            $html[] = "{$paragraph}";
        }

        shuffle($html);

        return implode(' ', $html);
    }

    public function htmlParagraphs(int $min = 1, int $max = 5, int $sentences = 10): string
    {
        $html = '';

        // Generate many paragraphs
        for ($k = 0; $k < $this->faker->numberBetween($min, $max); $k++) {
            $paragraph = $this->faker->paragraph($sentences);
            $html .= "<p>{$paragraph}</p>";
        }

        return $html;
    }

    public function message(bool $withImage = true, bool $withLink = true): string
    {
        $html = '';

        for ($k = 0; $k < $this->faker->numberBetween(2, 5); $k++) {
            $paragraph = $this->faker->paragraph();
            if ($this->faker->boolean(25)) {
                $paragraph .= " <strong>{$this->faker->sentence()}</strong>";
            }
            if ($this->faker->boolean(25)) {
                $paragraph .= " <em>{$this->faker->sentence()}</em>";
            }
            if ($this->faker->boolean(25)) {
                $paragraph .= " <code>{$this->faker->words(asText: true)}</code>";
            }
            if ($withLink && $this->faker->boolean(25)) {
                $paragraph .= " <a href=\"{$this->faker->url()}\">{$this->faker->words(asText: true)}</a>";
            }
            if ($withImage && $this->faker->boolean(15)) {
                $paragraph = "<a href=\"{$this->faker->imageUrl()}\" target=\"_blank\"><img src=\"{$this->faker->imageUrl()}\" alt=\"{$this->faker->sentence()}\" /></a>";
            }
            $html .= "<p>{$paragraph}</p>";
        }

        return $html;
    }

    /**
     * Generate rich body content.
     */
    public function richBody(bool $withTitle = true): string
    {
        $html = '';

        $dir = 'public/uploads';

        if (! File::exists($dir)) {
            File::makeDirectory($dir);
        }

        /*
         * Generate 1 title + block
         */
        for ($i = 0; $i < $this->faker->numberBetween(1, 1); $i++) {
            if ($withTitle) {
                $title = Str::title($this->faker->words($this->faker->numberBetween(5, 10), true));
                $html .= "<h2>{$title}</h2>";
            }

            /*
             * Generate 1 subtitle + block
             */
            for ($j = 0; $j < $this->faker->numberBetween(1, 2); $j++) {
                if ($withTitle) {
                    $title = Str::title($this->faker->words($this->faker->numberBetween(5, 10), true));
                    $html .= "<h3>{$title}</h3>";
                }

                /*
                 *  Generate many paragraphs
                 */
                for ($k = 0; $k < $this->faker->numberBetween(2, 5); $k++) {
                    $paragraph = $this->faker->paragraph(5);
                    $html .= "<p>{$paragraph}</p>";

                    /*
                     * Add image randomly
                     */
                    if ($this->faker->boolean(25)) {
                        // $source = self::randomMediaPath($faker, 'business');
                        // $image = basename($source);
                        // $target = "$dir/$image";

                        // if (! File::exists($target)) {
                        //     File::copy($source, $target);
                        // }
                        $image = $this->faker->imageUrl();
                        $img = "<img src=\"{$image}\" alt=\"\">";

                        $html .= "<p>{$img}</p>";
                    }
                }
            }
        }

        return $html;
    }

    /**
     * Generate markdown content.
     */
    public function markdown(): string
    {
        $markdown = '';

        // /*
        //  * Generate 1 title + block
        //  */
        // for ($i = 0; $i < $this->faker->numberBetween(1, 1); ++$i) {
        //     $title = Str::title($this->faker->words($this->faker->numberBetween(5, 10), true));
        //     $markdown .= "{$title}</h2>";

        //     /*
        //      * Generate 1 subtitle + block
        //      */
        //     for ($j = 0; $j < $this->faker->numberBetween(1, 2); ++$j) {
        //         $title = Str::title($this->faker->words($this->faker->numberBetween(5, 10), true));
        //         $markdown .= "<h3>{$title}</h3>";

        //         /*
        //          *  Generate many paragraphs
        //          */
        //         for ($k = 0; $k < $this->faker->numberBetween(2, 5); ++$k) {
        //             $paragraph = $this->faker->paragraph(5);
        //             $markdown .= "<p>{$paragraph}</p>";
        //         }
        //     }
        // }

        /*
        *  Generate many paragraphs
        */
        for ($k = 0; $k < $this->faker->numberBetween(2, 5); $k++) {
            $paragraph = $this->faker->paragraph(5);
            $markdown .= "{$paragraph}\n\n";
        }

        return $markdown;
    }

    /**
     * Generate timestamps.
     *
     * @return array<string,string> array{`created_at`:string,`updated_at`:string}
     */
    public function timestamps(string $minimum = '-20 years')
    {
        $created_at = Carbon::createFromTimeString(
            $this->faker->dateTimeBetween($minimum)
                ->format('Y-m-d H:i:s')
        )->format('Y-m-d H:i:s');
        $updated_at = Carbon::createFromTimeString(
            $this->faker->dateTimeBetween($created_at)
                ->format('Y-m-d H:i:s')
        )->format('Y-m-d H:i:s');

        return [
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }

    public function builder(string $builder): array
    {
        return FactoryBuilder::make($this, $builder);
    }

    private function setFactoryMedia(string|\UnitEnum|null $media_path = null)
    {
        if ($media_path && $media_path instanceof \UnitEnum) {
            $media_path = $media_path->name;
        }

        $this->media = new FactoryMedia($this, $media_path);

        return $this;
    }
}

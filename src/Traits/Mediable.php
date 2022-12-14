<?php

namespace Kiwilan\Steward\Traits;

use stdClass;

trait Mediable
{
    protected array $default_mediables = ['picture'];

    public function getMediablesListAttribute(): array
    {
        return property_exists($this, 'mediables') ? $this->mediables : $this->default_mediables; // @phpstan-ignore-line
    }

    /**
     * @return object
     */
    public function getMediableAttribute()
    {
        $mediable = new stdClass();
        foreach ($this->getMediablesListAttribute() as $field) {
            $mediable->{$field} = $this->mediable($field);
        }

        return $mediable;
    }

    /**
     * @return string|array|null
     */
    public function mediable(?string $field = 'picture', bool $get_path = false)
    {
        if ($field) {
            if (null === $this->{$field}) {
                return config('steward.media.default') ? config('steward.media.default') : null;
            }
            $path = $get_path ? $field : $this->{$field};

            if (is_array($this->{$field})) {
                $list = [];
                foreach ($this->{$field} as $media) {
                    $list[] = config('app.url')."/storage/{$media}";
                }

                return $list;
            }

            return config('app.url')."/storage/{$path}";
        }

        return null;
    }
}

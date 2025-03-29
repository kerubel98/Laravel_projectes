<?php

namespace pages\Pages;

abstract class SimplePage extends BasePage
{
    use \pages\Pages\Concerns\HasMaxWidth;
    use \pages\Pages\Concerns\HasTopbar;

    protected static string $layout = 'filament-panels::components.layout.simple';

    protected function getLayoutData(): array
    {
        return [
            'hasTopbar' => $this->hasTopbar(),
            'maxWidth' => $this->getMaxWidth(),
        ];
    }

    public function hasLogo(): bool
    {
        return true;
    }
}

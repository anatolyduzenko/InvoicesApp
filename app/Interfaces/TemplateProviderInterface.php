<?php

namespace App\Interfaces;

interface TemplateProviderInterface
{
    /**
     * @return array<string, array{name: string, view: string}>
     */
    public function getTemplates(): array;
}

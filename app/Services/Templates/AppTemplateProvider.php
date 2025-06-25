<?php

namespace App\Services\Templates;

use App\Interfaces\TemplateProviderInterface;

class AppTemplateProvider implements TemplateProviderInterface
{
    /**
     * Provides a list of available invoice templates.
     */
    public function getTemplates(): array
    {
        return [
            'basic' => [
                'name' => 'Basic Invoice Template',
                'view' => 'invoices.basic',
            ],
            'modern' => [
                'name' => 'Modern Invoice Template',
                'view' => 'invoices.modern',
            ],
        ];
    }
}

<?php 

namespace App\Services\Templates;

class TemplateManager
{
    protected array $providers;

    public function __construct(array $providers)
    {
        $this->providers = array_filter($providers);
    }

    /**
     * Retrieves a list of available templates.
     */
    public function all(): array
    {
        return collect($this->providers)
            ->flatMap(fn($provider) => $provider->getTemplates())
            ->toArray();
    }

    /**
     * Returns view name based on used template.
     */
    public function getViewByKey(string $key): ?string
    {
        $all = $this->all();
        return $all[$key]['view'] ?? null;
    }
}

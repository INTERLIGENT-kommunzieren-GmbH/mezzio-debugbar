<?php

declare(strict_types=1);

namespace Mezzio\DebugBar\Tests\Storage;

use DebugBar\Storage\StorageInterface;

use function array_slice;
use function time;

class MockStorage implements StorageInterface
{
    public array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function save(string $id, array $data): void
    {
        $this->data[$id] = $data;
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): array
    {
        return $this->data[$id];
    }

    /**
     * @inheritDoc
     */
    public function find(array $filters = [], int $max = 20, int $offset = 0): array
    {
        return array_slice($this->data, $offset, $max);
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        $this->data = [];
    }

    /**
     * @inheritDoc
     */
    public function prune(int $hours = 24): void
    {
        $threshold = time() - $hours * 3600;

        foreach ($this->data as $id => $entry) {
            if (($entry['time'] ?? 0) >= $threshold) {
                continue;
            }

            unset($this->data[$id]);
        }
    }
}

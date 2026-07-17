<?php

function buildComponentIndex(string $baseDir): array
{
  $index = [];

  $iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS)
  );

  foreach ($iterator as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
      continue;
    }

    $name = $file->getBasename('.php');

    if (isset($index[$name])) {
      trigger_error("Duplicate component name: {$name}", E_USER_WARNING);
      continue;
    }

    $index[$name] = $file->getPathname();
  }

  return $index;
}

function componentIndex(string $type): array
{
  static $cache = [];

  if (!isset($cache[$type])) {
    $baseDir = match ($type) {
      'elements' => __DIR__ . '/../elements',
      'parts' => __DIR__ . '/../parts',
      'layouts' => __DIR__ . '/../layouts',
      default => null,
    };

    if ($baseDir === null) {
      trigger_error("Unknown component type: {$type}", E_USER_WARNING);
      return [];
    }

    $cache[$type] = buildComponentIndex($baseDir);
  }

  return $cache[$type];
}

function renderComponent(string $type, string $name, array $args = []): void
{
  $index = componentIndex($type);

  if (!isset($index[$name])) {
    trigger_error(ucfirst($type) . " not found: {$name}", E_USER_WARNING);
    return;
  }

  include $index[$name];
}

function C_Elements(string $name, array $args = []): void
{
  renderComponent('elements', $name, $args);
}

function C_Parts(string $name, array $args = []): void
{
  renderComponent('parts', $name, $args);
}

function C_Layouts(string $name, array $args = []): void
{
  renderComponent('layouts', $name, $args);
}

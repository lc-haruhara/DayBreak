<?php
$schema_path = get_theme_file_path('/components/utilities/schema.json');
$schema_json = file_exists($schema_path) ? file_get_contents($schema_path) : '';
$schema = $schema_json ? json_decode($schema_json, true) : [];

$home = esc_url(home_url('/'));
$org = is_array($schema['organization'] ?? null) ? $schema['organization'] : [];
$entity = is_array($schema['entity'] ?? null) ? $schema['entity'] : [];

$filter_schema_value = static function ($value) use (&$filter_schema_value) {
  if (is_array($value)) {
    $filtered = [];

    foreach ($value as $key => $item) {
      $item = $filter_schema_value($item);

      if ($item === null) {
        continue;
      }

      if (is_array($item) && $item === []) {
        continue;
      }

      $filtered[$key] = $item;
    }

    return $filtered;
  }

  if ($value === null || $value === '') {
    return null;
  }

  return $value;
};

$organization_graph = [
  '@type' => $org['@type'] ?? 'Organization',
  '@id' => $home . '#organization',
  'name' => $org['name'] ?? get_bloginfo('name'),
  'url' => $home,
  'logo' => $org['logo'] ?? get_template_directory_uri() . '/images/ogp.png',
];

if (is_array($org['address'] ?? null)) {
  $organization_graph['address'] = $org['address'];
}

if (is_array($org['contactPoint'] ?? null)) {
  $organization_graph['contactPoint'] = $org['contactPoint'];
}

$organization_graph = $filter_schema_value($organization_graph);

$entity_graph = [];

if (!empty($entity['@type'])) {
  $entity_graph = $entity;
  $entity_slug = sanitize_title($entity['@id'] ?? $entity['@type']);

  $entity_graph['@id'] = $home . '#' . ($entity_slug !== '' ? $entity_slug : 'entity');
  $entity_graph['name'] = $entity_graph['name'] ?? get_bloginfo('name');
  $entity_graph['description'] = $entity_graph['description'] ?? get_bloginfo('description');

  if ($entity_graph['@type'] !== 'SoftwareApplication') {
    unset($entity_graph['applicationCategory'], $entity_graph['operatingSystem'], $entity_graph['offers']);
  }

  if (empty($entity_graph['provider']) && in_array($entity_graph['@type'], ['Service', 'SoftwareApplication'], true)) {
    $entity_graph['provider'] = [
      '@id' => $home . '#organization',
    ];
  }

  $entity_graph = $filter_schema_value($entity_graph);
}

$graph = [$organization_graph];

if ($entity_graph !== []) {
  $graph[] = $entity_graph;
}

$schema_output = [
  '@context' => 'https://schema.org',
  '@graph' => $graph,
];
?>

<script type="application/ld+json">
  <?php echo wp_json_encode($schema_output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
</script>
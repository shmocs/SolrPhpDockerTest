<?php
require 'vendor/autoload.php';

use Solarium\Client;
use Solarium\Core\Client\Adapter\Http as HttpAdapter;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Solarium\QueryType\Select\Query\Query;

// Create the HTTP adapter
$adapter = new HttpAdapter();

// Create an event dispatcher
$eventDispatcher = new EventDispatcher();

// Define your Solr endpoint configuration
$endpoints = array(
    'endpoint' => array(
        'localhost' => array(
            'host' => 'solr',
            'port' => 8983,
            'path' => '/',
            'core' => 'mycore',
        )
    )
);

// Create the Solr client
$client = new Client($adapter, $eventDispatcher, $endpoints);


// Create a select query
$query = new Query();

// Get search term from query parameter
$searchTerm = isset($_GET['q']) && !empty($_GET['q']) ? $_GET['q'] : ''; // Get search term or empty string

// Set the query to search within the 'name' field, or all fields if no term provided
if (!empty($searchTerm)) {
    $query->setQuery('name:' . $searchTerm);
} else {
    $query->setQuery('*:*'); // Search all documents
}

// Optionally set additional query options
$query->setRows(10); // Number of results per page

$highlight = $query->getHighlighting();
$highlight->setFields('name');
$highlight->setSimplePrefix('<strong style="color:red;">');
$highlight->setSimplePostfix('</strong>');

try {
    // Execute the query
    $resultSet = $client->select($query);
    // Output the results
    echo 'NumFound: '.$resultSet->getNumFound();
    echo "<h1>Search Results</h1>";
    foreach ($resultSet as $document) {
        $highlighting = $resultSet->getHighlighting();
        $highlightedDoc = $highlighting->getResult($document->id);
        if ($highlightedDoc && $highlightedDoc->getField('name')) {
            $name = implode(' ', $highlightedDoc->getField('name'));
        } else {
            $name = is_array($document->name) ? implode(', ', $document->name) : $document->name;
        }
        echo "<h2>" . html_entity_decode($name ?? 'No name') . "</h2>";
    }
} catch (Exception $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

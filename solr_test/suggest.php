<?php
require 'vendor/autoload.php';

use Solarium\Client;
use Solarium\Core\Client\Adapter\Http as HttpAdapter;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Solarium\QueryType\Select\Query\Query;

$adapter = new HttpAdapter();
$eventDispatcher = new EventDispatcher();

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

$client = new Client($adapter, $eventDispatcher, $endpoints);
$query = new Query();
// Get search term from query parameter
$searchTerm = isset($_GET['q']) && !empty($_GET['q']) ? $_GET['q'] : ''; // Get search term or empty string

// Set the query to search within the 'name' field, or all fields if no term provided
if (!empty($searchTerm)) {
    $query->setQuery('name:' . $searchTerm);
} else {
    $query->setQuery('*:*'); // Search all documents
}
$query->setRows(10);

try {
    $resultSet = $client->select($query);

    $rowCount = $resultSet->getNumFound();
    if ($rowCount > 0) {
        echo 'NumFound: ' . $rowCount;
        echo "<h1>Search Results</h1>";
        foreach ($resultSet as $document) {
            $name = is_array($document->name) ? implode(', ', $document->name) : $document->name;
            $description = is_array($document->description) ? implode(', ', $document->description) : $document->description;
            echo "<h2>" . htmlspecialchars($name ?? 'No name') . "</h2>";
            echo "<p>" . htmlspecialchars($description ?? 'No Description') . "</p>";
        }
    } else {
        $query = $client->createSuggester();

        $query->setDictionary('mySuggester'); // Name of the suggester component
        $query->setQuery($searchTerm); // The term to suggest
        $query->setCount(5); // Number of suggestions

        // Execute the query
        $result = $client->suggester($query);

        // Parse and display the suggestions
        $suggestions = $result->getResults();
        foreach ($suggestions as $suggestionResults) {
            // Loop through each suggestion result
            foreach ($suggestionResults as $suggestionResult) {
                // Extract the term suggestions (the array of suggestions)
                $suggestionsList = $suggestionResult->getSuggestions();
                foreach ($suggestionsList as $suggestion) {
                    echo 'Suggestion: ' . $suggestion['term'] . PHP_EOL;
                }
            }
        }
    }
} catch (Exception $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

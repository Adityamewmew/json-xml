<?php
header('Content-Type: application/xml');

$file = 'movie.xml';

if (!file_exists($file)) {
    $xml = new SimpleXMLElement('<movies/>');
    file_put_contents($file, $xml->asXML());
} else {
    $xml = simplexml_load_file($file);
}

// Dapatkan metode request HTTP
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Invalid JSON format.";
        exit;
    }

    if (isset($input['movies']) && is_array($input['movies'])) {
        foreach ($input['movies'] as $movieData) {
            $movie = $xml->addChild('movie');
            $movie->addChild('id', $movieData['id']);
            $movie->addChild('title', $movieData['title']);
            $movie->addChild('director', $movieData['director']);
            $movie->addChild('year', $movieData['year']);

            $genres = $movie->addChild('genres');
            foreach ($movieData['genres'] as $genre) {
                $genres->addChild('genre', $genre);
            }
        }

        file_put_contents($file, $xml->asXML());

        echo $xml->asXML();
    } else {
        echo "No 'movies' data found in the request.";
    }
} else {
    echo $xml->asXML();
}
?>

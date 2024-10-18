<?php
header('Content-Type: application/json');

// File path to store the films
$file = 'data.json';

// Cek jika file data sudah ada
if (!file_exists($file)) {
    // Jika file tidak ada, buat file dengan data default
    $films = [
        [
            "id" => 1,
            "judul" => "Avengers: Endgame",
            "tahun" => 2019,
            "genre" => "Action, Adventure, Sci-Fi",
            "sutradara" => "Anthony Russo, Joe Russo",
            "rating" => 8.4
        ],
        [
            "id" => 2,
            "judul" => "The Matrix",
            "tahun" => 1999,
            "genre" => "Action, Sci-Fi",
            "sutradara" => "Lana Wachowski, Lilly Wachowski",
            "rating" => 8.7
        ]
    ];
    file_put_contents($file, json_encode($films));
} else {
    // Ambil data film dari file
    $films = json_decode(file_get_contents($file), true);
}

// Mendapatkan metode HTTP yang digunakan (GET, POST, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

// Mengatur respon berdasarkan metode HTTP
switch ($method) {
    case 'GET':
        // Mengembalikan semua data films
        echo json_encode($films);
        break;

    case 'POST':
        // Mendapatkan data dari body request
        $input = json_decode(file_get_contents('php://input'), true);
        $input['id'] = end($films)['id'] + 1; // Menambahkan ID baru
        $films[] = $input; // Menambahkan data baru ke array

        // Simpan data film terbaru ke file
        file_put_contents($file, json_encode($films));

        // Mengirimkan data yang baru ditambahkan
        echo json_encode($input);
        break;

    case 'DELETE':
        // Mendapatkan ID dari URL
        $url_parts = explode('/', $_SERVER['REQUEST_URI']);
        $id = end($url_parts);

        // Cek apakah ID valid dan ada dalam data
        $films = array_filter($films, function ($film) use ($id) {
            return $film['id'] != $id;
        });

        // Simpan data terbaru setelah penghapusan
        file_put_contents($file, json_encode(array_values($films)));

        
    default:
        // Metode HTTP tidak didukung
        http_response_code(405);
        echo json_encode(["message" => "Metode HTTP tidak didukung"]);
        break;
}
?>

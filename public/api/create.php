<?php
require_once __DIR__ . '/../../app/_include.php';
$labels = [
    'title' => 'タイトル',
];
$data = mb_trims($input->post(array_keys($labels)));
$v = new Validation([
    'label' => [
        'title' => 'タイトル'
    ],
    'rules' => [
        'required'  => ['title'],
        'lengthMax' => [['title', 100]],
    ],
]);
if (!$v->do($data)) {
    return output_error($v->errors());
}
$sql = "INSERT INTO todo (title) VALUES (:title);";
$db->query($sql, DB::generate_query_params($data));
return $db->last_insert_id() ? output_success() : output_error();

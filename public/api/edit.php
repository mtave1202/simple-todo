<?php
require_once __DIR__ . '/../../app/_include.php';
$labels = [
    'id'    => 'ID',
    'title' => 'タイトル',
];
$data = mb_trims($input->post(array_keys($labels)));
$v = new Validation([
    'labels' => $labels,
    'rules' => [
        'required'  => ['id', 'title'],
        'lengthMax' => [['title', 100]],
    ],
]);
if (!$v->do($data)) {
    return output_error($v->errors());
}
$sql = 'UPDATE todo SET title = :title WHERE id = :id';
$result = $db->query($sql, DB::generate_query_params($data));
return $result->rowCount() ? output_success() : output_error("編集に失敗しました");

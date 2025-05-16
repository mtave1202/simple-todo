<?php
require_once __DIR__ . '/../../app/_include.php';
$labels = [
    'id'     => 'ID',
    'status' => '状態',
];
$data = mb_trims($input->post(array_keys($labels)));
$v = new Validation([
    'labels' => $labels,
    'rules' => [
        'required'  => ['id', 'status'],
    ],
]);
if (!$v->do($data)) {
    return output_error($v->errors());
}
$sql = "UPDATE todo SET status = :status WHERE id = :id";
$result = $db->query($sql, DB::generate_query_params($data));
return $result->rowCount() ? output_success() : output_error('更新に失敗しました');

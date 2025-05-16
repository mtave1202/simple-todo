<?php
require_once __DIR__ . '/../../app/_include.php';
$id = trim($input->post("id"));
if (!$id || is_integer($id)) {
    return output_error("不正な値が設定されました");
}
$sql = "UPDATE todo SET deleted_at = :deleted_at WHERE id = :id";
$result = $db->query($sql, [
    ':id' => $id,
    ':deleted_at' => date('Y-m-d H:i:s'),
]);
return $result->rowCount() ? output_success() : output_error("削除に失敗しました");

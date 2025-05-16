<?php
require_once __DIR__ . '/../../app/_include.php';
$sql = 'SELECT `id`, `status`, `title` FROM todo where deleted_at is null ORDER BY `status`, `created_at`';
$result = $db->query($sql);
if (!$result) {
    return output_error();
}
$array = array_column($result->fetchAll(PDO::FETCH_ASSOC), null, 'id');
return output_success(null, [
    'data'   => $array,
]);
